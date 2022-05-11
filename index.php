
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Storage App</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="./Weather.css">
</head>
<body>

    <h1> Weather in Tulsa </h1>
    <span class="temp">
            <img alt="temp" height="100" width="100">
            <p id="Temperature">Temperature:</p>
            <p id="Description">Description:</p>
            <p id="when">When:</p>
            <p id="wind">Wind:</p>
            <p id="pressure">Pressure:</p>
            <p id="Humidity">Humidity:</p>
            
</body>
<script type="text/javascript">
    
    // Register service worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('sw.js').then(function(registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful');
            }, function(err) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', err);
            }).catch(err => {
                if(localStorage.when != null) {
                    document.getElementsByTagName("img")[0].setAttribute("src",`http://openweathermap.org/img/wn/${localStorage.icon}@2x.png`)

                    let freshness = Math.round((Date.now() - localStorage.when)/1000) + " second(s) ago";
                    document.getElementById("Description").innerHTML += localStorage.myWeather;
                    document.getElementById("Temperature").innerHTML += localStorage.myTemperature;
                    document.getElementById("when").innerHTML += freshness ;
                    document.getElementById("wind").innerHTML +=localStorage.myWind;
                    document.getElementById("pressure").innerHTML +=localStorage.pressure +"hpa";
                    document.getElementById("Humidity").innerHTML +=localStorage.humidity+"%";
                    // document.getElementById("icon").innerHTML +=localStorage.icon;
                    // No local cache, access network
                }
                // Display errors in console
                console.log(err);   

            });
        });
     
    }    // Check browser cache first, use if there and less than 10 seconds old
    if(localStorage.when != null && parseInt(localStorage.when) + 10000 > Date.now()) {
        console.log("from localstorage: " + localStorage.when);
        document.getElementsByTagName("img")[0].setAttribute("src",`http://openweathermap.org/img/wn/${localStorage.icon}@2x.png`)

            let freshness = Math.round((Date.now() - localStorage.when)/1000) + " second(s) ago";
            document.getElementById("Description").innerHTML += localStorage.myWeather;
            document.getElementById("Temperature").innerHTML += localStorage.myTemperature;
            document.getElementById("when").innerHTML += freshness ;
            document.getElementById("wind").innerHTML +=localStorage.myWind;
            document.getElementById("pressure").innerHTML +=localStorage.pressure +"hpa";
            document.getElementById("Humidity").innerHTML +=localStorage.humidity+"%";
            // document.getElementById("icon").innerHTML +=localStorage.icon;
    // No local cache, access network
    }else{  
   
        axios.get(`http://localhost/weather_storage/my_api.php?city=Tulsa`) 
        .then(function (response) {
            console.log(response); 
            const kelvinToCelsius = Math.round(response ['data']['weather_temperature'])-273+"°C";
            document.getElementById('Temperature').innerHTML += kelvinToCelsius;
            document.getElementById('Description').innerHTML += response['data']['weather_description'];
            document.getElementById('wind').innerHTML +=response['data']['weather_wind'];
            document.getElementById('when').innerHTML +=response ['data']['weather_when'];
            document.getElementById('pressure').innerHTML +=response['data']['weather_pressure'] + "hPa";
            document.getElementById('Humidity').innerHTML +=response['data']['weather_humidity'] + "%";
        
            let iconId = response['data']['icon'];
            console.log(iconId);
            document.getElementsByTagName("img")[0].setAttribute("src",`http://openweathermap.org/img/wn/${iconId}@2x.png`)
            // Save new data to browser, with new timestamp
            localStorage.myWeather = response['data']['weather_description'];
            localStorage.myTemperature = kelvinToCelsius;
            localStorage.myWind= response['data']['weather_wind'];
            let datenow =  Date.now();
            localStorage.when = datenow; // milliseconds since January 1 1970
            localStorage.pressure=response['data']['weather_pressure'];
            localStorage.humidity=response['data']['weather_humidity'];
            localStorage.icon=response['data']['icon'];
        }).catch(err => {
            // Display errors in console
            console.log(err);
        }); 
    }
</script>
</html>

     