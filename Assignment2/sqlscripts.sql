REM   Script: Session 06
REM   ass2

CREATE TABLE Users ( 
    UserID INT PRIMARY KEY, 
    Username VARCHAR(255), 
    Email VARCHAR(255), 
    Password VARCHAR(255), 
    Age INT, 
    Name VARCHAR(255) 
);

CREATE TABLE BodyMeasurement ( 
    BodyMeasurementID INT PRIMARY KEY, 
    Height DECIMAL(5, 2), 
    Weight DECIMAL(5, 2), 
    BodyMassIndex DECIMAL(5, 2), 
    BodyFatPercentage DECIMAL(5, 2), 
    UserID INT UNIQUE, -- One-to-one relationship with Users table 
    FOREIGN KEY (UserID) REFERENCES Users(UserID) 
);

CREATE TABLE Workout ( 
    WorkoutID INT PRIMARY KEY, 
    ExerciseCategory VARCHAR(255), 
    ExercisePlan VARCHAR(255), 
    ExerciseLevel VARCHAR(255), 
    OnlineTutorial VARCHAR(255) 
);

CREATE TABLE Cardio ( 
    CardioID INT PRIMARY KEY, 
    AerobicClass DECIMAL(5, 2), 
    CaloriesBurned DECIMAL(5, 2), 
    RunningTrack DECIMAL(5, 2), 
    CardioMachines VARCHAR(255), 
     WorkoutID INT, -- Foreign key to WorkoutType 
    FOREIGN KEY (WorkoutID) REFERENCES Workout(WorkoutID) 
);

CREATE TABLE WeightTraining ( 
    WeightTrainingID INT PRIMARY KEY, 
    SetNumber INT, 
    MinReps INT, 
    MaxReps INT, 
    MinWeight DECIMAL(5, 2), 
    MaxWeight DECIMAL(5, 2), 
    WorkoutID INT, -- Foreign key to WorkoutType 
    FOREIGN KEY (WorkoutID) REFERENCES Workout(WorkoutID) 
);

CREATE TABLE Beginner ( 
    BeginnerID INT PRIMARY KEY, 
    SafetyTips VARCHAR(255), 
    CoachSupervision VARCHAR(255), 
    Instructions VARCHAR(255), 
    WorkoutID INT, -- Foreign key to WorkoutType 
    FOREIGN KEY (WorkoutID) REFERENCES Workout(WorkoutID) 
);

CREATE TABLE Advanced ( 
    AdvancedID INT PRIMARY KEY, 
    AdvancedTechnique DECIMAL(5, 2), 
    Prerequisite DECIMAL(5, 2), 
    PersonalRecordsChart DECIMAL(5, 2), 
    TargetedMuscle VARCHAR(255), 
    WorkoutID INT, -- Foreign key to WorkoutType 
    FOREIGN KEY (WorkoutID) REFERENCES Workout(WorkoutID) 
);

CREATE TABLE Location ( 
    LocationID INT PRIMARY KEY, 
    City VARCHAR(255), 
    State VARCHAR(255), 
    ZipCode VARCHAR(10), 
    UserID INT, -- One-to-many relationship with Users table 
    FOREIGN KEY (UserID) REFERENCES Users(UserID) 
);

CREATE TABLE BasicMembership ( 
    BasicMembershipID INT PRIMARY KEY, 
    Fee DECIMAL(5, 2), 
    RenewalDate DATE, 
    ContractLength INT, 
    GoodyBag VARCHAR(255), 
    UserID INT, -- One-to-many relationship with Users table 
    FOREIGN KEY (UserID) REFERENCES Users(UserID) 
);

CREATE TABLE ProMembership ( 
    ProMembershipID INT PRIMARY KEY, 
    MassageChair VARCHAR(255), 
    OnlineCourse VARCHAR(255), 
    Drinks VARCHAR(255), 
    Shower VARCHAR(255), 
    AdditionalFeatures VARCHAR(255), 
    UserID INT, -- One-to-many relationship with Users table 
    FOREIGN KEY (UserID) REFERENCES Users(UserID) 
);

CREATE TABLE ProPlusMembership ( 
    ProPlusMembershipID INT PRIMARY KEY, 
    Spa VARCHAR(255), 
    StoneSona VARCHAR(255), 
    SteamSona VARCHAR(255), 
    PersonalTrainer VARCHAR(255), 
    UserID INT, -- One-to-many relationship with Users table 
    FOREIGN KEY (UserID) REFERENCES Users(UserID) 
);
