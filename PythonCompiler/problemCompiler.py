import sys, os
import filecmp as fcmp
import subprocess as sub
import time
import threading
import shlex
import argparse
import math

WrongNoSource       = "Fisierul nu are extensia suportata!"
WrongDefault        = "A intervenit o eroare!"

def report_error(error_message):
    print("{\n\t\"statusCode\" : \"400\"\n}", end="")
    exit(-1)

def delete_file(filename):
    if os.path.isfile(filename):
        os.remove(filename)

def build_source(sourceFileName, tergetFileName):

    if not os.path.isfile(sourceFileName):
        exit(-1)

    if os.path.isfile(tergetFileName):
        delete_file(tergetFileName)

    prePath = os.path.splitext(sourceFileName)

    executabileFolder = os.path.split(os.path.abspath(sourceFileName))

    os.system("vcvars32 > NUL & cd " + executabileFolder[0] + " & cl /nologo " + sourceFileName + " /Fe: "  + tergetFileName + " > NUL")
   
    objFileName = prePath[0] + ".obj"
    if os.path.isfile(objFileName):
        delete_file(objFileName)


def silently_kill_process(procname):
	# pentru siguranta, omoara de fiecare data procesul
	os.system('taskkill /f /im ' + procname + " > temp.txt 2> temp1.txt")
	delete_file("temp.txt")
	delete_file("temp1.txt")

def run_cmd(executableFile, inputFile, outputFile):
	fnull = open(os.devnull,'w')
	p = sub.Popen(executable=executableFile, args=[], stdin=inputFile, stdout=outputFile, stderr=fnull)
	try:
		val = p.wait(15)
	except:
		return 2

	if (val==0):#asta intoarce programul daca e cu succes!
		return 1
	else:
		return 0

def run_test(executabileFolder, executableFile, inputPath, outputPath, testNumber):
	
    #check for each in0,1,3,etc to be same as out0,1,2
    inputFilePath = inputPath + "/in" + str(testNumber) + ".txt"
    refOutputFile = outputPath + "/out" + str(testNumber) + ".txt"

    inputFile = open(inputFilePath, 'r')
    outputFile = open(refOutputFile, 'w')

    run_cmd(executableFile, inputFile, outputFile)

    inputFile.close()
    outputFile.close()

if __name__ == '__main__':	
	
    parser = argparse.ArgumentParser()
    parser.add_argument("sourcePath")
    args = parser.parse_args()

    executabileFolder, exeName = os.path.split(os.path.abspath(args.sourcePath))

    inputPath = executabileFolder + "/Inputs"
    outputPath = executabileFolder + "/Outputs"
    
    numberOfTests =  len([name for name in os.listdir(inputPath) if os.path.isfile(os.path.join(inputPath, name))])

    prePath, extension = os.path.splitext(args.sourcePath)

    tergetFileName = prePath + ".exe"
    if (extension == ".c") or (extension == ".cpp"):
        build_thread = threading.Thread(target=build_source, kwargs=dict(sourceFileName=args.sourcePath, tergetFileName=tergetFileName))
        build_thread.start()
        build_thread.join()
        if not os.path.isfile(tergetFileName):
            report_error(WrongDefault)	

    else:
        report_error(WrongNoSource)
        
    executabileFolder, exeName = os.path.split(os.path.abspath(tergetFileName))

    for i in range(0, numberOfTests):
        run_test(executabileFolder, tergetFileName, inputPath, outputPath, i)
        silently_kill_process(exeName)

    numberOfOutputs =  len([name for name in os.listdir(outputPath) if os.path.isfile(os.path.join(outputPath, name))])

    if numberOfOutputs == numberOfTests:
        print("{\n\t\"statusCode\" : \"200\"\n}", end="")

    delete_file(tergetFileName)

        