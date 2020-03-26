/**
   httpUpdate.ino
    Created on: 27.11.2015
*/

#include <Arduino.h>

#include <WiFi.h>

#include <HTTPClient.h>
#include <HTTPUpdate.h>

//Update these values
const char *ssid = "SSID";
const char *password = "PASSWORD";
String serverAddress = "esp.mydomain.com";
String serverPath = "/update.php";
String currentVersion = "1.0";

int attempts = 5;

// The port to listen for incoming TCP connections
#define LISTEN_PORT           80
// Create an instance of the server
WiFiServer server(LISTEN_PORT);

void setup() {

  Serial.begin(115200);
  // Serial.setDebugOutput(true);

  Serial.println();
  Serial.println();
  Serial.println();

  for (uint8_t t = 4; t > 0; t--) {
    Serial.printf("[SETUP] WAIT %d...\n", t);
    Serial.flush();
    delay(1000);
  }

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
      delay(500);
    }
  Serial.print(WiFi.localIP()); // Print the IP address

}

void loop()
{
  if (attempts > 0)
  {
    attempts--;
    WiFiClient client;

    t_httpUpdate_return ret = httpUpdate.update(client, serverAddress, 80, serverPath, currentVersion);

    switch (ret)
    {
    case HTTP_UPDATE_FAILED:
      Serial.printf("HTTP_UPDATE_FAILED Error (%d): %s\n", httpUpdate.getLastError(), httpUpdate.getLastErrorString().c_str());
      break;

    case HTTP_UPDATE_NO_UPDATES:
      Serial.println("HTTP_UPDATE_NO_UPDATES");
      break;

    case HTTP_UPDATE_OK:
      Serial.println("HTTP_UPDATE_OK");
      break;
    }
  }
}