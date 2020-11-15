console.log("ok");

fetch(
  "https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=51.5&lon=0"
)
  .then((response) => {
    return response.json();
  })
  .then((myJson) => {
    console.log(myJson["geometry"]["type"]);
  });
