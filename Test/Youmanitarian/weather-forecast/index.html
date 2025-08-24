<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.0.0/dist/full.js"></script>
</head>
<body class="bg-gradient-to-r from-green-100 to-green-200 text-green-800 p-5 min-h-screen">
    <div class="container mx-auto max-w-6xl">
        <h1 class="text-3xl font-bold text-center mb-8 text-green-900"><i class='bx bx-leaf'></i> Weather App</h1>

        <!-- Weather Form -->
        <div class="bg-white/90 p-6 rounded-xl shadow-lg mb-6">
            <form id="weatherForm" class="flex flex-col md:flex-row gap-3 mb-4">
                <input type="text" id="cityInput" class="border border-green-500 rounded-lg px-4 py-2 flex-grow" placeholder="Enter a city">
                <input type="text" id="provinceInput" class="border border-green-500 rounded-lg px-4 py-2 flex-grow" placeholder="Enter a Province">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class='bx bx-search'></i> Search
                </button>
                <button id="locationBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class='bx bx-current-location'></i> Use My Location
                </button>
            </form>
        </div>

        <!-- Weather Results -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white/90 p-6 rounded-xl shadow-lg" id="weatherResults"></div>
            <div class="bg-white/90 p-6 rounded-xl shadow-lg" id="weatherResults2"></div>
        </div>

        <!-- Farming Advisory -->
        <div class="bg-amber-100/90 p-4 rounded-xl shadow-lg mb-6">
            <div class="flex items-center gap-2">
                <i class='bx bx-info-circle text-xl'></i>
                <strong class="text-lg">Farming Advisory:</strong>
                <span id="advisory" class="font-medium">Select location to view advisories</span>
            </div>
        </div>

        <!-- 7-Day Forecast -->
        <h3 class="text-xl font-semibold mb-4"><i class='bx bx-calendar'></i> 7-Day Forecast</h3>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3" id="weeklyForecast"></div>

        <h2 class="text-xl font-bold text-center mt-4"><i class='bx bx-calendar'></i> Hourly Forecast</h2>
        <div class="flex overflow-x-auto space-x-4 p-4"  id="hourlyForecast"></div>
        <!-- Pinned Locations -->
        <div class="bg-white/90 p-6 rounded-xl shadow-lg mt-6 max-w-md mx-auto">
            <h3 class="text-2xl font-bold text-green-800 flex items-center space-x-2">
                <i class="bx bx-pin text-xl"></i>
                <span>Pinned Locations</span>
            </h3>
            <div id="pinnedLocations" class="mt-4 space-y-4">
                <!-- JS Display -->
            </div>
        </div>


        <div class="bg-white/90 p-6 rounded-xl shadow-lg mt-6">
            <h3 class="text-xl font-bold text-green-800">🌡️ Weather Terminology Guide</h3>
        
            <div class="mt-4">
                <h4 class="text-lg font-semibold text-green-700">📌 Atmospheric Pressure (hPa)</h4>
                <p class="text-gray-700 text-sm">Atmospheric pressure helps predict weather conditions.</p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li><strong>🔻 Below 1000 hPa:</strong> Low pressure → Rain or storms are likely.</li>
                    <li><strong>🔺 Above 1015 hPa:</strong> High pressure → Clear and dry weather.</li>
                </ul>
            </div>
        
            <div class="mt-4">
                <h4 class="text-lg font-semibold text-green-700">💧 Humidity (%)</h4>
                <p class="text-gray-700 text-sm">Humidity affects plant health and human comfort.</p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li><strong>🌿 Below 40%:</strong> Dry air, plants lose moisture quickly.</li>
                    <li><strong>🌧️ Above 80%:</strong> High risk of mold, fungus, and plant diseases.</li>
                </ul>
            </div>
        
            <div class="mt-4">
                <h4 class="text-lg font-semibold text-green-700">🌦️ Precipitation (mm)</h4>
                <p class="text-gray-700 text-sm">Precipitation measures how much water falls from the sky, including rain, snow, or hail.</p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li><strong>0 mm:</strong> No rain expected.</li>
                    <li><strong>1-5 mm:</strong> Light rain, may slightly wet the ground.</li>
                    <li><strong>10-20 mm:</strong> Moderate rain, noticeable wet conditions.</li>
                    <li><strong>20+ mm:</strong> Heavy rain, possible flooding risks.</li>
                </ul>
            </div>

            <div class="mt-4">
                <h4 class="text-lg font-semibold text-green-700">🌧️ Rain Chance (%)</h4>
                <p class="text-gray-700 text-sm">Rain chance indicates how likely it is to rain in a given period.</p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li><strong>0-20%:</strong> Very unlikely to rain.</li>
                    <li><strong>30-50%:</strong> Possible scattered showers.</li>
                    <li><strong>60-80%:</strong> Likely to rain, bring an umbrella! ☂️</li>
                    <li><strong>90-100%:</strong> Almost certain rain or storms expected.</li>
                </ul>
            </div>

            <div class="mt-4">
                <h4 class="text-lg font-semibold text-green-700">📊 Rain Chance vs. Precipitation</h4>
                <p class="text-gray-700 text-sm"><strong>🔹 Are they the same? No!</strong></p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li>✔ <strong>Precipitation (mm)</strong> → How much rain will fall.</li>
                    <li>✔ <strong>Rain Chance (%)</strong> → How likely it is to rain.</li>
                </ul>
                <p class="text-sm text-gray-700 font-medium mt-3">💡Examples:</p>
                <ul class="text-sm text-gray-600 list-disc ml-5 mt-2">
                    <li>🌥️ <strong>20% Rain Chance & 0 mm Precipitation</strong> → Clouds present, but very low chance of rain.</li>
                    <li>🌦️ <strong>80% Rain Chance & 5 mm Precipitation</strong> → High chance of light rain.</li>
                    <li>⛈️ <strong>90% Rain Chance & 30 mm Precipitation</strong> → Very likely and heavy rain expected!</li>
                </ul>
            </div>
        </div>
        
        
    </div>

    <script>
        const API_KEY = "9505fd1df737e20152fbd78cdb289b6a";
        // const API_KEY = "5de15b7804e0078404a629b6de093fb2";
        const form = document.getElementById("weatherForm");
        const cityInput = document.getElementById("cityInput");
        const provinceInput = document.getElementById("provinceInput");
        const weatherResults = document.getElementById("weatherResults");
        const weatherResults2 = document.getElementById("weatherResults2");
        const hourlyForecast = document.getElementById("hourlyForecast");
        const weeklyForecast = document.getElementById("weeklyForecast");
        const advisory = document.getElementById("advisory");
        const locationBtn = document.getElementById("locationBtn");
        const USE_ONE_CALL_API = true;

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const city = cityInput.value.trim();
            const province = provinceInput.value.trim();
            
            if (city) {
                getWeather(city, province);
            }
        });


        locationBtn.addEventListener("click", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => getWeatherByCoords(position.coords.latitude, position.coords.longitude),
                    () => alert("Location access denied")
                );
            }
        });

        function getWeather(city, province = "") {
            let query = `${city},PH`;
            if (province.trim()) {
                query = `${city},${province},PH`;  
            }

            fetch(`https://api.openweathermap.org/geo/1.0/direct?q=${query}&limit=5&appid=${API_KEY}`)
                .then(res => res.json())
                .then(locations => {
                    if (locations.length === 0) {
                        weatherResults.innerHTML = "<p class='text-red-500'>Location not found. Try again.</p>";
                        return;
                    }

                    const location = locations[0];
                    getWeatherByCoords(location.lat, location.lon);
                })
                .catch(() => weatherResults.innerHTML = "<p class='text-red-500'>Error fetching location data</p>");
        }


        function getWeatherByCoords(lat, lon) {
            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric`)
                .then(res => res.json())
                .then(data => {
                    displayWeather(data);
                    displayWeather2(data);
                    displayAdvisory(data);

                    // Pin
                    weatherResults.innerHTML += `
                        <button onclick="pinLocation('${data.name}', ${lat}, ${lon})"
                            class="mt-3 px-4 py-2 rounded-lg 
                                ${isPinned(lat, lon) ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'} 
                                text-white flex items-center space-x-2"
                            ${isPinned(lat, lon) ? 'disabled' : ''}>
                            <i class="bx ${isPinned(lat, lon) ? 'bx-check-circle' : 'bx-pin'} text-lg"></i>
                            <span>
                                ${isPinned(lat, lon) ? "✅ Already Pinned" : "📌 Pin Location"}
                            </span>
                        </button>

                    `;
                });
        }

        function displayWeather(data) {
            const currentTime = new Date();
            const formattedTime = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = currentTime.toLocaleDateString('en-US', options);

            const weatherInfo = getFriendlyWeatherDescription(data.weather[0].description);

            weatherResults.innerHTML = `
                <h2 class="text-xl font-bold text-gray-800">${data.name}, ${data.sys.country}</h2>
                
                <!-- Display weather image and text -->
                <div class="flex items-center space-x-2">
                    <img src="${weatherInfo.image}" alt="${weatherInfo.text}" class="w-16 h-16">
                    <p class="text-lg text-gray-600 capitalize">${weatherInfo.text}</p>
                </div>

                <p class="text-2xl font-bold text-blue-500">${data.main.temp}°C</p> 
                <p class="text-sm text-gray-500">Updated as of ${formattedTime}</p>
                <p class="text-sm text-gray-600 font-medium mt-2">${formattedDate}</p>
            `;

            getWeeklyForecast(data.coord.lat, data.coord.lon);
            displayHourlyForecast(data.coord.lat, data.coord.lon);
        }


        function displayWeather2(data) {
            const currentTime = new Date();
            const formattedTime = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = currentTime.toLocaleDateString('en-US', options);
            const sunrise = new Date(data.sys.sunrise * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const sunset = new Date(data.sys.sunset * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const precipitation = data.rain ? (data.rain["1h"] || 0) : 0;

            fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${data.coord.lat}&lon=${data.coord.lon}&appid=${API_KEY}&units=metric`)
                .then(res => res.json())
                .then(forecastData => {
                    const rainChance = forecastData.list[0].pop ? (forecastData.list[0].pop * 100).toFixed(0) : "0";

                    weatherResults2.innerHTML = `
                        <div class="mt-2 text-gray-700">
                            <div class="flex">
                                <div>
                                    <p><i class='bx bx-thermometer'></i> Feels like: ${data.main.feels_like.toFixed(2)}°C</p>
                                    <p><i class='bx bx-water'></i> Humidity: ${data.main.humidity}%</p> 
                                    <p><i class='bx bx-bar-chart-alt-2'></i> Pressure: ${data.main.pressure} mb</p>
                                    <p><i class='bx bx-wind'></i> Wind: ${(data.wind.speed * 3.6).toFixed(2)} Km/H</p>
                                    <p><i class='bx bx-cloud-rain'></i> Precipitation: ${precipitation} mm</p>
                                    <p><i class='bx bx-droplet'></i> Rain Chance: ${rainChance}%</p>
                                </div>
    
                                <div>
                                    <div class="flex items-center mt-2">
                                        <img src="images/Additional/sunrise.png" alt="Sunrise" class="w-8 h-8 mr-2">
                                        <p class="text-gray-700 font-medium">Sunrise: ${sunrise}</p>
                                    </div>
        
                                    <div class="flex items-center mt-2">
                                        <img src="images/Additional/sunset.png" alt="Sunset" class="w-8 h-8 mr-2">
                                        <p class="text-gray-700 font-medium">Sunset: ${sunset}</p>
                                    </div>
                                </div>
                            </divc>
                        </div>
                    `;
                });
        }

        function displayAdvisory(data) {
            const temp = data.main.temp;
            const wind = data.wind.speed;
            const weather = data.weather[0].main.toLowerCase();
            let message = "";

            if (weather.includes("rain")) {
                message = "🌧️ High rainfall expected - postpone outdoor activities.";
            } else if (temp > 35) {
                message = "🔥 Extreme heat warning - protect crops and livestock.";
            } else if (wind > 10) {
                message = "💨 High winds - secure equipment and structures.";
            } else {
                message = "🌾 Favorable conditions for farming activities.";
            }

            advisory.innerHTML = message;
        }

        // function getWeeklyForecast(lat, lon) {
        //     fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric`)
        //         .then(res => res.json())
        //         .then(data => {
        //             weeklyForecast.innerHTML = "";

        //             const daily = {};
        //             data.list.forEach(entry => {
        //                 let date = entry.dt_txt.split(" ")[0];
        //                 if (!daily[date]) {
        //                     const weatherInfo = getFriendlyWeatherDescription(entry.weather[0].description);
                            
        //                     daily[date] = {
        //                         minTemp: entry.main.temp_min,
        //                         maxTemp: entry.main.temp_max,
        //                         weather: weatherInfo.text,
        //                         icon: weatherInfo.image,
        //                         rainChance: entry.pop ? (entry.pop * 100).toFixed(0) : "0",
        //                         fullDate: new Date(entry.dt_txt),
        //                     };
        //                 } else {
        //                     daily[date].minTemp = Math.min(daily[date].minTemp, entry.main.temp_min);
        //                     daily[date].maxTemp = Math.max(daily[date].maxTemp, entry.main.temp_max);
        //                 }
        //             });

        //             const forecastArray = Object.values(daily).slice(0, 7);

        //             forecastArray.forEach(day => {
        //                 const options = { weekday: 'short', month: 'short', day: 'numeric' };
        //                 const formattedDate = day.fullDate.toLocaleDateString('en-US', options);

        //                 weeklyForecast.innerHTML += `
        //                     <div class="bg-white/90 p-4 rounded-xl shadow-lg flex flex-col items-center text-center">
        //                         <p class="text-sm font-semibold">${formattedDate}</p>
        //                         <img src="${day.icon}" alt="${day.weather}" class="w-20 h-20">
        //                         <p class="text-sm text-gray-700">${day.weather}</p>
        //                         <p class="text-sm font-medium text-blue-500 flex items-center space-x-2">
        //                             <span class="text-red-500 font-bold">${day.maxTemp.toFixed(1)}°C</span> 
        //                             <span class="text-gray-500">/</span> 
        //                             <span class="text-blue-500 font-bold">${day.minTemp.toFixed(1)}°C</span>
        //                         </p>
        //                         <p class="text-sm text-gray-700 font-medium">🌧️ ${day.rainChance}% Rain</p>
        //                     </div>
        //                 `;
        //                 //  <p class="text-lg font-bold text-blue-500">${day.maxTemp.toFixed(1)}°C / ${day.minTemp.toFixed(1)}°C</p>
        //             });
        //         });
        // }

        function displayHourlyForecast(lat, lon) {
    fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric`)
        .then(res => res.json())
        .then(data => {
            hourlyForecast.innerHTML = ""; // Clear previous results

            const currentDate = new Date().toISOString().split("T")[0]; 
            const todayForecast = data.list.filter(entry => entry.dt_txt.startsWith(currentDate)); // Filter only today's forecast
            // const todayForecast = data.list.filter(entry => new Date(entry.dt * 1000) > now); // Only future times

            todayForecast.forEach(hour => {
                const time = new Date(hour.dt * 1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: true });
                const temp = hour.main.temp.toFixed(1);
                const weatherIcon = `https://openweathermap.org/img/wn/${hour.weather[0].icon}.png`;
                const rainChance = hour.pop ? (hour.pop * 100).toFixed(0) : "0"; // Probability of precipitation in %

                hourlyForecast.innerHTML += `
                    <div class="flex flex-col items-center p-2 bg-white/90 rounded-lg shadow-md">
                        <p class="text-sm font-semibold">${time}</p>
                        <img src="${weatherIcon}" alt="${hour.weather[0].description}" class="w-10 h-10">
                        <p class="text-sm text-gray-700">${hour.weather[0].description}</p>
                        <p class="text-sm font-bold text-blue-500">${temp}°C</p>
                        <p class="text-sm text-gray-700">🌧️ ${rainChance}% Rain</p>
                    </div>
                `;
            });
        })
        .catch(error => console.error("Error fetching hourly forecast:", error));
}


        function getWeeklyForecast(lat, lon) {
    fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric`)
        .then(res => res.json())
        .then(data => {
            weeklyForecast.innerHTML = "";

            const daily = {};
            data.list.forEach(entry => {
                let date = entry.dt_txt.split(" ")[0];
                if (!daily[date]) {
                    const weatherInfo = getFriendlyWeatherDescription(entry.weather[0].description);
                    
                    let rainChance = entry.pop ? (entry.pop * 100).toFixed(0) : "0";
                    if (rainChance < 1) {
                        rainChance = "0";  // Show as 0% if below 1% chance
                    }

                    let precipitation = entry.rain ? entry.rain['3h'] : 0;
                    
                    daily[date] = {
                        minTemp: entry.main.temp_min,
                        maxTemp: entry.main.temp_max,
                        weather: weatherInfo.text,
                        icon: weatherInfo.image,
                        rainChance: rainChance,
                        precipitation: precipitation, 
                        fullDate: new Date(entry.dt_txt),
                    };
                } else {
                    daily[date].minTemp = Math.min(daily[date].minTemp, entry.main.temp_min);
                    daily[date].maxTemp = Math.max(daily[date].maxTemp, entry.main.temp_max);
                    
                    if (entry.pop > daily[date].rainChance) {
                        daily[date].rainChance = entry.pop;
                    }
                    
                    // Accumulate precipitation (in mm)
                    if (entry.rain && entry.rain['3h']) {
                        daily[date].precipitation += entry.rain['3h'];
                    }
                }
            });

            const forecastArray = Object.values(daily).slice(0, 7);

            forecastArray.forEach(day => {
                const options = { weekday: 'short', month: 'short', day: 'numeric' };
                const formattedDate = day.fullDate.toLocaleDateString('en-US', options);

                weeklyForecast.innerHTML += `
                    <div class="bg-white/90 p-4 rounded-xl shadow-lg flex flex-col items-center text-center">
                        <p class="text-sm font-semibold">${formattedDate}</p>
                        <img src="${day.icon}" alt="${day.weather}" class="w-20 h-20">
                        <p class="text-sm text-gray-700">${day.weather}</p>
                        <p class="text-sm font-medium text-blue-500 flex items-center space-x-2">
                            <span class="text-red-500 font-bold">${day.maxTemp.toFixed(1)}°C</span> 
                            <span class="text-gray-500">/</span> 
                            <span class="text-blue-500 font-bold">${day.minTemp.toFixed(1)}°C</span>
                        </p>
                        <p class="text-sm text-gray-700 font-medium">🌧️ ${day.rainChance}% Rain</p>
                        <p class="text-sm text-gray-700 font-medium">💧 ${day.precipitation.toFixed(1)} mm Precipitation</p>
                    </div>
                `;
            });
        });
}



        function getFriendlyWeatherDescription(description) {
            const weatherMapping = {
                "clear sky": { text: "Clear Sky", image: "images/v1/sunny.png" },
                "few clouds": { text: "Mostly Sunny", image: "images/v1/sunny_s_cloudy.png" },
                "scattered clouds": { text: "Partly Cloudy", image: "images/v1/partly_cloudy.png" },
                "broken clouds": { text: "Cloudy", image: "images/v1/cloudy_s_sunny.png" },
                "overcast clouds": { text: "Overcast", image: "images/v1/cloudy.png" },
                "light rain": { text: "Light Rain", image: "images/v1/rain_light.png" },
                "moderate rain": { text: "Moderate Rain", image: "images/v1/rain.png" },
                "heavy intensity rain": { text: "Heavy Rain", image: "images/v1/rain_heavy.png" },
                "thunderstorm": { text: "⚡ Thunderstorm", image: "images/v1/thunderstorms.png" },
                // "mist": { text: "Misty", image: "images/v1/fog.png" },
                // "haze": { text: "Hazy", image: "images/v1/fog.png" },
                // "fog": { text: "Foggy", image: "images/v1/fog.png" },
                // "snow": { text: "Snow", image: "images/v1/snow.png" },
                // "drizzle": { text: "Light Drizzle", image: "images/v1/rain_light.png" },
                // "rain and snow": { text: "Rain and Snow", image: "images/v1/rain_s_snow.png" },
                // "rain showers": { text: "Rain Showers", image: "images/v1/rain_s_cloudy.png" },
                // "snow showers": { text: "Snow Showers", image: "images/v1/snow_s_cloudy.png" },
                // "heavy snow": { text: "Heavy Snow", image: "images/v1/snow_heavy.png" },
                // "light snow": { text: "Light Snow", image: "images/v1/snow_light.png" },
                "sunny with rain": { text: "Sunny with Rain", image: "images/v1/sunny_s_rain.png" },
                "sunny with clouds": { text: "unny with Clouds", image: "images/v1/sunny_s_cloudy.png" },
            };

            return weatherMapping[description.toLowerCase()] || { text: description, image: "images/v1/default.png" };
        }










    // Pin Location

    const pinLimit = 3;
    const pinnedLocations = JSON.parse(localStorage.getItem("pinnedLocations")) || [];
    const pinnedLocationsDiv = document.getElementById("pinnedLocations");

    // Check if location is already pinned
    function isPinned(lat, lon) {
        return pinnedLocations.some(loc => loc.lat === lat && loc.lon === lon);
    }

    // save pinned locations to localStorage
    function savePinnedLocations() {
        localStorage.setItem("pinnedLocations", JSON.stringify(pinnedLocations));
        displayPinnedLocations();
    }

    // add a location to pinned
    function pinLocation(name, lat, lon) {
        if (pinnedLocations.length >= pinLimit) {
            alert("You have reached the maximum number of pinned locations.");
            return;
        }

        if (isPinned(lat, lon)) {
            alert("Location is already pinned!");
            return;
        }

        pinnedLocations.push({ name, lat, lon });
        savePinnedLocations();
    }

    // remove a pinned location 
    function removePinnedLocation(lat, lon) {
        const index = pinnedLocations.findIndex(loc => loc.lat === lat && loc.lon === lon);
        if (index !== -1) {
            pinnedLocations.splice(index, 1);
            savePinnedLocations();
        }
    }

    // display pinned locations
    function displayPinnedLocations() {
        pinnedLocationsDiv.innerHTML = "";

        if (pinnedLocations.length === 0) {
            pinnedLocationsDiv.innerHTML = "<p class='text-gray-600'>No locations pinned yet.</p>";
            return;
        }

        pinnedLocations.forEach(loc => {
            const locationDiv = document.createElement("div");
            locationDiv.classList.add("bg-green-100", "p-3", "rounded-lg", "flex", "justify-between", "items-center");

            locationDiv.innerHTML = `
                <p class="text-lg font-semibold text-gray-800">${loc.name}</p>
<div class="space-x-4 flex items-center">
    <!-- View Forecast Button with Icon -->
    <button 
        onclick="getWeatherByCoords(${loc.lat}, ${loc.lon})" 
        class="btn btn-primary bg-blue-500 text-white hover:bg-blue-600 flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200">
        <i class="bx bx-calendar text-xl"></i>
        <span>View Forecast</span>
    </button>

    <!-- Remove Button with Icon -->
    <button 
        onclick="removePinnedLocation(${loc.lat}, ${loc.lon})" 
        class="btn btn-danger bg-red-500 text-white hover:bg-red-600 flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200">
        <i class="bx bx-trash text-xl"></i>
        <span>Remove</span>
    </button>
</div>

            `;

            pinnedLocationsDiv.appendChild(locationDiv);
        }); 
    }

    document.addEventListener("DOMContentLoaded", displayPinnedLocations);
    </script>

</body>
</html>
