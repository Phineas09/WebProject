
function loadCar() {





    let formData = new FormData();
    
    formData.append('car', true);

    var request = new XMLHttpRequest();
	request.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            var response = JSON.parse(this.responseText);
            if(response.statusCode == 200) {
                
                let car = JSON.parse(response.car);

                let height = car["galerie"].length;
                let galerie = car["galerie"];

                let string = '<table>\
                    <tr>\
                        <td>Model</td>\
                        <td colspan="' + height + '">' + car["model"] + '</td>\
                    </tr>\
                    <tr>\
                        <td>An</td>\
                        <td colspan="' + height + '">' + car["An"] + '</td>\
                    </tr>\
                    <tr>\
                        <td>Echipare</td>\
                        <td colspan="' + height + '">' + car["nivel_echipare"] + '</td>\
                    </tr>\
                    <tr>\
                        <td>Pret</td>\
                        <td colspan="' + height + '">' + car["pret"] + '</td>\
                    </tr>\
                    <tr>\
                        <td>Galerie</td>\
                        <td>' + galerie[0]["poza1"] + '</td>\
                        <td>' + galerie[1]["poza2"] + '</td>\
                        <td>' + galerie[2]["poza3"] + '</td>\
                    </tr>\
                    <tr>\
                        <td>Poza</td>\
                        <td><img id="overview" src="' + galerie[0]["url"] + '" class="image"></td>\
                        <td><img img id="interior" src="' + galerie[1]["url"] + '" class="image"></td>\
                        <td><img img id="fata" src="' + galerie[2]["url"] + '" class="image"></td>\
                    </tr>\
                </table>';

                document.getElementById("content").innerHTML = string;

            }
        }
    };
    
	request.open('POST', './Lab6.php');
	request.send(formData);
}


loadCar();