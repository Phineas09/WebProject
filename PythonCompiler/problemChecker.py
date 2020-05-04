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
    print("{\"statusCode\" : \"420\", \"message\" : \"", end="")
    print(error_message, end="")
    print("\"}", end="")
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

    os.system("vcvars32 > NUL & cd " + executabileFolder[0] + " & cl /nologo " + os.path.abspath(sourceFileName) + " /Fe: "  + os.path.abspath(tergetFileName) + " > NUL")
   
    objFileName = prePath[0] + ".obj"
    if os.path.isfile(objFileName):
        delete_file(objFileName)


def silently_kill_process(procname):
	# pentru siguranta, omoara de fiecare data procesul
	os.system('taskkill /f /im ' + procname + " > temp.txt 2> temp1.txt")
	delete_file("temp.txt")
	delete_file("temp1.txt")

def compare_files(file1,file2):

    if not os.path.isfile(file1):
        return "Failed"

    if not os.path.isfile(file2):
        return "Failed"

    if (fcmp.cmp(file1,file2)):
        return "Passed"
    else:
        return "Failed"


def run_cmd(executableFile, inputFile, outputFile):

	fnull = open(os.devnull,'w')

	p = sub.Popen(executable=executableFile, args=[], stdin=inputFile, stdout=outputFile, stderr=fnull)

	try:
		val = p.wait(10)
	except:
		return 2
	
	if (val==0):#asta intoarce programul daca e cu succes!
		return 1
	else:
		return 0


def run_test(exeName, executabileFolder, executableFile, inputPath, outputPath, testNumber):
	
    #check for each in0,1,3,etc to be same as out0,1,2
    inputFilePath = inputPath + "/in" + str(testNumber) + ".txt"

    outputFilePath = executabileFolder + "/" + exeName + "out" + str(testNumber) + ".txt"
	
    inputFile = open(inputFilePath, 'r')
    outputFile = open(outputFilePath, 'w')

    output = run_cmd(executableFile, inputFile, outputFile)
    if(output == 0):
        return "Failed"

    if(output == 2):
        return "Time limit exceeded"

    refOutputFile = outputPath + "/out" + str(testNumber) + ".txt"

    inputFile.close()
    outputFile.close()
    
    _retrunVal = compare_files(outputFilePath,refOutputFile)
    delete_file(outputFilePath)
    return _retrunVal

def print_test_result(index,value):
    string = "\"" + str(index) + "\" : \"" + value + "\","
    print (string, end="")

if __name__ == '__main__':	
	
    parser = argparse.ArgumentParser()
    parser.add_argument("sourcePath")
    parser.add_argument("inputPath")
    parser.add_argument("outputPath")
    parser.add_argument("problemPoints")
    args = parser.parse_args()

    numberOfTests =  len([name for name in os.listdir(args.inputPath) if os.path.isfile(os.path.join(args.inputPath, name))])
    numberOfOutputs =  len([name for name in os.listdir(args.outputPath) if os.path.isfile(os.path.join(args.outputPath, name))])

    if numberOfOutputs != numberOfTests:
        report_error("Number of tests is inconsistent!")

    # If inputs == outputs continue 

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
        
    score = 0
    one_test_ponder = float(args.problemPoints) / numberOfTests

    executabileFolder, exeName = os.path.split(os.path.abspath(tergetFileName))

    print("{", end="")

    for i in range(0, numberOfTests):
        val = run_test(exeName, executabileFolder, tergetFileName, args.inputPath, args.outputPath, i)
        silently_kill_process(exeName)
        print_test_result(i,val)
        if val == "Passed":
            score += one_test_ponder

    print ("\"score\" : \"", end="")
    print (math.ceil(score),end="")
    print ("\", \"statusCode\" : \"200\" }", end="")
    delete_file(tergetFileName)