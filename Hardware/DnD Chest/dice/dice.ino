
//int dieSelectorPlus = 10;//nano7
//int dieSelectorMinus = 11;//nano8
//int rollDiePin = 12;//nano9

int dieSelectorPlus = 7;//nano7
int dieSelectorMinus = 8;//nano8
int rollDiePin = 9;//nano9

int dieLedPins[] = {3, 4, 5, 6, 7, 8};
int dieMaxNumbers[] = {4, 6, 8, 10, 12, 20};
int dieLedPinCount = 0;

int dieSelector = 0;

int bitsCount = 0;
int bits[] = {1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0};

int rolled = 0;
int latchPin = 22;//3||
int clockPin = 24;//5||
int dataPin = 26;//6||
byte leds = 0;

//#define SER    26  // 6 ||nano4 data in
//#define SRCLK  24  // 5 ||nano3 shift register clock
//#define SRCLR  30  // 4 ||nano6 clear shift register
//#define RCLK   22  // 3 ||nano2 storage register (sometimes called the latch pin)
//#define OE     28  // 2 ||nano5 enable output

#define SER    4  // 6 ||nano4 data in
#define SRCLK  3  // 5 ||nano3 shift register clock
#define SRCLR  6  // 4 ||nano6 clear shift register
#define RCLK   2  // 3 ||nano2 storage register (sometimes called the latch pin)
#define OE     5  // 2 ||nano5 enable output

void setup()
{
  Serial.begin(9600);
  long seed = analogRead(A0); 
  Serial.print("seed: ");
  Serial.println(seed);
  randomSeed(seed);
  dieLedPinCount = sizeof(dieLedPins) / sizeof(int);
  bitsCount = sizeof(bits) / sizeof(int);
  // put your setup code here, to run once:
  for (int i = 0; i < dieLedPinCount; i++)
  {
    pinMode(dieLedPins[i], OUTPUT);
  }

  pinMode(dieSelectorPlus, INPUT_PULLUP);
  pinMode(dieSelectorMinus, INPUT_PULLUP);
  pinMode(rollDiePin, INPUT_PULLUP);

  pinMode(latchPin, OUTPUT);
  pinMode(dataPin, OUTPUT);
  pinMode(clockPin, OUTPUT);

  //new
  pinMode(SER, OUTPUT);
  pinMode(SRCLK, OUTPUT);
  pinMode(SRCLR, OUTPUT);
  pinMode(RCLK, OUTPUT);
  pinMode(OE, OUTPUT);
  clearShiftRegisters();
  turnOutputsOn();
  //end new

  Serial.print("dieLedPinCount: ");
  Serial.print(dieLedPinCount);
  // updateDieSelection();

  Serial.println();
  Serial.print("dieSelector: ");
  Serial.print(dieSelector);

  Serial.println();
}
void old_updateShiftRegister()
{
  digitalWrite(latchPin, LOW);
  shiftOut(dataPin, clockPin, LSBFIRST, leds);
  digitalWrite(latchPin, HIGH);
}
void loop() {


  if (digitalRead(dieSelectorPlus) == LOW)
  {
    dieSelector = constrain(dieSelector + 1, 0, dieLedPinCount - 1);

    //  updateDieSelection();
    Serial.print("plus: ");
    Serial.print(dieSelector);
    Serial.println();
    delay(500);
  }
  if (digitalRead(dieSelectorMinus) == LOW)
  {
    dieSelector = constrain(dieSelector - 1, 0, dieLedPinCount - 1);

    // updateDieSelection();
    Serial.print("minus: ");
    Serial.print(dieSelector);
    Serial.println();
    delay(500);
  }

  if (digitalRead(rollDiePin) == LOW)
  {
    Serial.print("rolled: ");
    rolled = rollDie(dieSelector);
    //leds = 0;
    //updateShiftRegister();

    //bitSet(leds, rolled - 1);
    //updateShiftRegister();

    Serial.println(rolled);
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


  //lightFirstN(dieSelector);
  calculateBits();
  displayBits();
}

void old_updateDieSelection()
{
  old_deactivateAll();
  digitalWrite(dieLedPins[dieSelector], HIGH);

}

void old_deactivateAll()
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


///////////////new
void lightFirstN(int n) {
  clearShiftRegisters();
  int i;
  for (i = 0; i < n; ++i)
  {
    shiftDataIn(HIGH);
  }
  copyShiftToStorage();
  //delay(200);
}

void displayBits()
{
  clearShiftRegisters();

  for (int i = 0; i < bitsCount; i++)
  {
    shiftDataIn(bits[i]);
  }

  copyShiftToStorage();
}

void calculateBits()
{
  writeDiceSelection();
  writeRolled();
}

void writeDiceSelection()
{

  bits[0] = dieSelector == 0 ? HIGH : LOW; //d4
  bits[1] = dieSelector == 1 ? HIGH : LOW; //d6
  bits[2] = dieSelector == 2 ? HIGH : LOW; //d8
  bits[3] = dieSelector == 3 ? HIGH : LOW; //d10
  bits[4] = dieSelector == 4 ? HIGH : LOW; //d12
  bits[5] = dieSelector == 5 ? HIGH : LOW; //d20
}

void writeRolled()
{
  //if rolled 20, overwrite all to HIGH
  if (rolled == 20)
  {
    for (int i = 0; i < bitsCount; i++)
    {
      bits[i] = HIGH;
    }
  }
  else {
    bits[6] = rolled >= 10 ? HIGH : LOW; //rolled >= 10

    // get one-digit of rolled
    int onedigitRolled = rolled % 10;
    //  Serial.println(onedigitRolled);

    bits[7] = onedigitRolled >= 9 ? HIGH : LOW;
    bits[8] = onedigitRolled >= 8 ? HIGH : LOW;
    bits[9] = onedigitRolled >= 7 ? HIGH : LOW;
    bits[10] = onedigitRolled >= 6 ? HIGH : LOW;
    bits[11] = onedigitRolled >= 5 ? HIGH : LOW;
    bits[12] = onedigitRolled >= 4 ? HIGH : LOW;
    bits[13] = onedigitRolled >= 3 ? HIGH : LOW;
    bits[14] = onedigitRolled >= 2 ? HIGH : LOW;
    bits[15] = onedigitRolled >= 1 ? HIGH : LOW;
  }
}

//helpers
// This doesn't change the value stored in the storage registers.
void turnOutputsOn() {
  digitalWrite(OE, LOW);
}

// This doesn't change the value stored in the storage registers.
void turnOutputsOff() {
  digitalWrite(OE, HIGH);
}

// clear the shift registers without affecting the storage registers.
void clearShiftRegisters() {
  digitalWrite(SRCLR, LOW);
  digitalWrite(SRCLR, HIGH);
}

// All the data in the shift registers moves over 1 unit and the new data goes in at shift register 0.
// The data that was in the shift register 7 goes to the next register (if any).
void shiftDataIn(int data) {
  digitalWrite(SER, data);
  digitalWrite(SRCLK, HIGH);
  digitalWrite(SRCLK, LOW);
}

// copy the 8 shift registers into the shift registers,
// which changes the output voltage of the pins.
void copyShiftToStorage() {
  digitalWrite(RCLK, HIGH);
  digitalWrite(RCLK, LOW);
}
