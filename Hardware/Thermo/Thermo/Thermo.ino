#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Adafruit_BME280.h>

//const char* ssid = "FRITZ!Box 7430 HV";
//const char* password = "97364848481831321284";
const char* ssid = "Office Bonlanden";
const char* password = "852355722528517399258";
//const char* ssidalt = "FRITZ!Box 7490i";
//const char* passwordalt = "85215572252851731258";

const char* host = "192.168.178.21";
const int httpPort = 80;

WiFiClientSecure client;

//temp stuff

#define BME_SCK 13
#define BME_MISO 12
#define BME_MOSI 11
#define BME_CS 10

#define SEALEVELPRESSURE_HPA (1013.25)
Adafruit_BME280 bme;
unsigned long delayTime;
const String hostAdress = "http://xn--ruben-mller-zhb.de/uptime/temp.php";
const String deviceID = "device5";

void setup() {
  Serial.begin(115200);
  Serial.println();
  while (!Serial);   // time to get serial running
  Serial.println(F("BME280 test"));
  Serial.print("connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  unsigned status;

  // default settings
  // (you can also pass in a Wire library object like &Wire2)
  status = bme.begin(0x76);
  if (!status) {
    Serial.println("Could not find a valid BME280 sensor, check wiring, address, sensor ID!");
    Serial.print("SensorID was: 0x"); Serial.println(bme.sensorID(), 16);
    Serial.print("        ID of 0xFF probably means a bad address, a BMP 180 or BMP 085\n");
    Serial.print("   ID of 0x56-0x58 represents a BMP 280,\n");
    Serial.print("        ID of 0x60 represents a BME 280.\n");
    Serial.print("        ID of 0x61 represents a BME 680.\n");
    while (1);
  }
  Serial.println("-- Default Test --");
  delayTime =  10000;//120000;//every two minues

  send();
}

int value = 0;

void loop() {}
void send() {

  if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status

    printValues();


    HTTPClient http;  //Declare an object of class HTTPClient
    String str = hostAdress + "?device=" + deviceID + "&value=" + String(bme.readTemperature()) + "&pressure=" + String(bme.readPressure() / 100.0F) + "&humidity=" + String(bme.readHumidity());
    http.begin(str);
    int httpCode = http.GET();

    if (httpCode > 0) { //Check the returning code

      String payload = http.getString();   //Get the request response payload
      Serial.println(payload);                     //Print the response payload

    }

    http.end();   //Close connection

  }
  //delay(delayTime);
  ESP.deepSleep(300e6);
}


void printValues() {
  Serial.print("Temperature = ");
  Serial.print(bme.readTemperature());
  Serial.println(" *C");

  Serial.print("Pressure = ");

  Serial.print(bme.readPressure() / 100.0F);
  Serial.println(" hPa");

  Serial.print("Approx. Altitude = ");
  Serial.print(bme.readAltitude(SEALEVELPRESSURE_HPA));
  Serial.println(" m");

  Serial.print("Humidity = ");
  Serial.print(bme.readHumidity());
  Serial.println(" %");

  Serial.println();
}
