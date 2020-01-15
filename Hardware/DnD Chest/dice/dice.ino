
int dieSelectorPlus = 10;
int dieSelectorMinus = 11;
int rollDiePin = 12;
int dieLedPins[] = {3, 4, 5, 6, 7, 8, 9};
int dieMaxNumbers[] = {4, 6, 8, 10, 12, 20, 100};
int dieLedPinCount = 0;

int dieSelector = 0;

void setup()
{
  Serial.begin(9600);
  long seed = analogRead(A0);
  Serial.print("seed: ");
  Serial.println(seed);
  randomSeed(seed);
  dieLedPinCount = sizeof(dieLedPins) / sizeof(int);
  // put your setup code here, to run once:
  for (int i = 0; i < dieLedPinCount; i++)
  {
    pinMode(dieLedPins[i], OUTPUT);
  }

  pinMode(dieSelectorPlus, INPUT_PULLUP);
  pinMode(dieSelectorMinus, INPUT_PULLUP);
  pinMode(rollDiePin, INPUT_PULLUP);


  Serial.print("dieLedPinCount: ");
  Serial.print(dieLedPinCount);
  updateDieSelection();

  Serial.println();
  Serial.print("dieSelector: ");
  Serial.print(dieSelector);

  Serial.println();
}

void loop() {

  if (digitalRead(dieSelectorPlus) == LOW)
  {
    dieSelector = constrain(dieSelector + 1, 0, dieLedPinCount - 1);

    updateDieSelection();
    Serial.print("plus: ");
    Serial.print(dieSelector);
    Serial.println();
    delay(500);
  }
  if (digitalRead(dieSelectorMinus) == LOW)
  {
    dieSelector = constrain(dieSelector - 1, 0, dieLedPinCount - 1);

    updateDieSelection();
    Serial.print("minus: ");
    Serial.print(dieSelector);
    Serial.println();
    delay(500);
  }

  if (digitalRead(rollDiePin) == LOW)
  {
    Serial.print("rolled: ");
    Serial.println(rollDie(dieSelector));
    delay(500);
  }

  //  for (int i = 0; i < sizeof(dieLedPins); i++)
  //  {
  //    digitalWrite(dieLedPins[i], HIGH);
  //    delay(1000);
  //    digitalWrite(dieLedPins[i], LOW);
  //    delay(1000);
  //  }

  //  digitalWrite(dieLedPins[dieSelector], HIGH);
  //  delay(1000);
  //  digitalWrite(dieLedPins[dieSelector], LOW);
  //  delay(1000);



}

void updateDieSelection()
{
  deactivateAll();
  digitalWrite(dieLedPins[dieSelector], HIGH);

}

void deactivateAll()
{
  for (int i = 0; i < dieLedPinCount; i++)
  {
    digitalWrite(dieLedPins[i], LOW);
  }
}

int rollDie(int dieIndex)
{
  return random(1, dieMaxNumbers[dieIndex] + 1);
}
