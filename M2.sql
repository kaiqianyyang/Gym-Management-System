drop table ASSESSMENTRESULT_ASSESS cascade constraints;
drop table CERTIFY cascade constraints;
drop table CUSTOMER cascade constraints;
drop table EQUIPMENT_DEPOSIT cascade constraints;
drop table EQUIPMENT_INVENTORY cascade constraints;
drop table GROUPLESSON_ACTIVITYDURATION cascade constraints;
drop table INCLUDES cascade constraints;
drop table MEMBERSHIP cascade constraints;
drop table ORDER_SELL_RENEW cascade constraints;
drop table PERSONALTRAINERCERTIFICATION cascade constraints;
drop table PERSONALTRAININGPACKAGE cascade constraints;
drop table PRODUCT cascade constraints;
drop table RENT cascade constraints;
drop table SALESPERSON cascade constraints;
drop table STAFF cascade constraints;
drop table TRAIN cascade constraints;
drop table TRAINER cascade constraints;
drop table GroupLesson_Details cascade constraints;

CREATE TABLE Staff (
    StaffID          CHAR(20)   PRIMARY KEY,
    FirstName        CHAR(20), 
    LastName         CHAR(20),
    Email            CHAR(30),
    PhoneNumber      CHAR(20),
    SIN              CHAR(20)   UNIQUE
);

CREATE TABLE Trainer (
    StaffID          CHAR(20)   PRIMARY KEY,
    FOREIGN KEY (StaffID) REFERENCES Staff(StaffID) 
);

CREATE TABLE Salesperson (
    StaffID          CHAR(20)   PRIMARY KEY,
    TotalSaleAmount  FLOAT      DEFAULT 0,
    FOREIGN KEY (StaffID) REFERENCES Staff  
);

CREATE TABLE Product (
    ProductID        CHAR(20)   PRIMARY KEY
);

CREATE TABLE Customer (
    CustomerID       CHAR(20)   PRIMARY KEY,
    FirstName        CHAR(20), 
    LastName         CHAR(20),
    Email            CHAR(30),
    PhoneNumber      CHAR(20),
    MembershipExpireDate   DATE, 
    PrivateLessonRemaining int DEFAULT 0
);

CREATE TABLE Membership (
    ProductID        CHAR(20)   PRIMARY KEY,
    MonthlyFee       FLOAT,
    NumOfMonths      INTEGER,
    FOREIGN KEY (ProductID) REFERENCES Product
        ON DELETE CASCADE
);

CREATE TABLE PersonalTrainingPackage (
    ProductID        CHAR(20)   PRIMARY KEY,
    EntryFee         FLOAT,
    NumOfSessions    INTEGER,
    FOREIGN KEY (ProductID) REFERENCES Product
        ON DELETE CASCADE
);

CREATE TABLE Order_Sell_Renew (
    OrderNo          CHAR(30) PRIMARY KEY, 
    OSR_Date             DATE     NOT NULL,
    TotalPrice       FLOAT    DEFAULT 0,
    CustomerID       CHAR(20) NOT NULL,
    StaffID          CHAR(20) NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer,
    FOREIGN KEY (StaffID) REFERENCES Salesperson
);

CREATE TABLE Includes (
    ProductID        CHAR(20),
    OrderNo          CHAR(30),
    PRIMARY KEY (ProductID, OrderNo),
    FOREIGN KEY (ProductID) REFERENCES Product,
    FOREIGN KEY (OrderNo) REFERENCES Order_Sell_Renew
        ON DELETE CASCADE
);

CREATE TABLE AssessmentResult_Assess (
    CustomerID       CHAR(20),
    AA_Date             DATE,
    Weight           FLOAT  DEFAULT 0,
    Height           FLOAT  DEFAULT 0,
    BMI              FLOAT  DEFAULT 0,
    BodyFatPct       FLOAT  DEFAULT 0,
    MuscleMass       FLOAT  DEFAULT 0,
    PRIMARY KEY (CustomerID, AA_Date),
    FOREIGN KEY (CustomerID) REFERENCES Customer
        ON DELETE CASCADE
);

CREATE TABLE Equipment_Deposit (
    EqType          CHAR(20)   PRIMARY KEY,
    Deposit         FLOAT      DEFAULT 0
);

CREATE TABLE Equipment_Inventory (
    EqID            CHAR(20),
    EqType          CHAR(20), 
    IsAvailable     CHAR(3)     DEFAULT 'YES',
    PRIMARY KEY (EqID, EqType),
    FOREIGN KEY (EqType) REFERENCES Equipment_Deposit
);

CREATE TABLE Rent (
    CustomerID      CHAR(20), 
    EqID            CHAR(20), 
    EqType          CHAR(20),
    Rent_Date            DATE, 
    PRIMARY KEY (CustomerID, EqID, EqType),
    FOREIGN KEY (CustomerID) REFERENCES Customer
                    ON DELETE CASCADE,
    FOREIGN KEY (EqID, EqType) REFERENCES Equipment_Inventory
                    ON DELETE CASCADE
);

CREATE TABLE Train(
    TimeSlot        INTEGER, 
    Train_Date      DATE, 
    CustomerID      CHAR(20), 
    StaffID         CHAR(20),
    PRIMARY KEY (TimeSlot, Train_Date, CustomerID, StaffID),
    FOREIGN KEY (CustomerID) REFERENCES Customer
                    ON DELETE CASCADE,
    FOREIGN KEY (StaffID) REFERENCES Trainer
                    ON DELETE CASCADE
);

CREATE TABLE PersonalTrainerCertification(
    Name           CHAR(20)    PRIMARY KEY
);

CREATE TABLE Certify (
    Name            CHAR(20), 
    StaffID         CHAR(20), 
    Certify_Date    DATE, 
    PRIMARY KEY (Name, StaffID),
    FOREIGN KEY (Name) REFERENCES PersonalTrainerCertification,
    FOREIGN KEY (StaffID) REFERENCES Trainer
                    ON DELETE CASCADE
);

CREATE TABLE GroupLesson_ActivityDuration(
    ActivityType   CHAR(20)    PRIMARY KEY,
    Duration       INTEGER     DEFAULT 45
);

CREATE TABLE GroupLesson_Details(
    StartTime      CHAR(20), 
    GD_Date        DATE, 
    ActivityType   CHAR(20), 
    NumOfSpotsLeft INTEGER      DEFAULT 20,
    StaffID        CHAR(20)     NOT NULL, 
    PRIMARY KEY (StartTime, GD_Date, ActivityType),
    FOREIGN KEY (StaffID) REFERENCES Trainer,
    FOREIGN KEY (ActivityType) REFERENCES GroupLesson_ActivityDuration
);

insert into Staff values ('101', 'Bob','Smith','Bobssmith@gmail.com','6238762202', '123456654321117');
insert into Staff values ('102', 'Kayln','Walter', 'Kaylnwwalter@gmail.com', '4232345552', '123456654321251');
insert into Staff values ('103', 'Alex','Mark', 'Alexmmark@gmail.com', '5524442345', '123456654328811');
insert into Staff values ('104', 'Kara','Rops', 'Kararrops@gmail.com', '332559022', '123456654321123');
insert into Staff values ('105', 'Candace','Conne', 'Candacecconne@gmail.com', '4545232357', '123456654324511');
insert into Staff values ('201', 'Liam','Jones','Liamjjones@gmail.com','6238762202', '123456654321111');
insert into Staff values ('202', 'Elijah','Browns', 'Elijahbbrowns@gmail.com', '4232345552', '123456654321131');
insert into Staff values ('203', 'Lucas','Taylor', 'Lucasttaylor@gmail.com', '5524442345', '123456654321221');
insert into Staff values ('204', 'Charlotte','Clark', 'Charlottecclark@gmail.com', '332559022', '123456654321112');
insert into Staff values ('205', 'Emma','Scott', 'Emmasscott@gmail.com', '4545232357', '123456654321133');
insert into Staff values ('206', 'Oscar','Scott', 'liggemini@gmail.com', '67727339', '13857689636');

insert into Trainer values ('101');
insert into Trainer values ('102');
insert into Trainer values ('103');
insert into Trainer values ('104');
insert into Trainer values ('105');
insert into Trainer values ('201');

insert into Salesperson values ('201', 3.00);
insert into Salesperson values ('202', 4.00);
insert into Salesperson values ('203', 5.00);
insert into Salesperson values ('204', 6.00);
insert into Salesperson values ('205', 8.00);

insert into Product values ('11111111');
insert into Product values ('11111112');
insert into Product values ('11111113');
insert into Product values ('11111114');
insert into Product values ('11111115');
insert into Product values ('22222221');
insert into Product values ('22222222');
insert into Product values ('22222223');
insert into Product values ('22222224');
insert into Product values ('22222225');

insert into Membership values ('11111111', 75.00, 1);
insert into Membership values ('11111112', 60.00, 2);
insert into Membership values ('11111113', 44.33, 3);
insert into Membership values ('11111114', 36.33, 6);
insert into Membership values ('11111115', 26.67, 12);

insert into PersonalTrainingPackage values ('22222221', 50.00, 5);
insert into PersonalTrainingPackage values ('22222222', 46.25, 16);
insert into PersonalTrainingPackage values ('22222223', 42.19, 32);
insert into PersonalTrainingPackage values ('22222224', 36.67, 72);
insert into PersonalTrainingPackage values ('22222225', 29.00, 108);


insert into Customer values ('10001', 'Amelia', 'Nelly', 'Ameliannelly@gmail.com', '4039543991', '11-DEC-2021', 2);
insert into Customer values ('10002', 'Ava', 'Freda', 'Avaffreda@gmail.com', '2503652794', '11-MAR-2022', 23);
insert into Customer values ('10003', 'Osca', 'Jim', 'Oscajjim@gmail.com', '2895715072', '24-AUG-2022', 15);
insert into Customer values ('10004', 'Ivy', 'Du', 'Ivyddu@gmail.com', '8199567549', '13-DEC-2021', 2);
insert into Customer values ('10005', 'Arthur', 'Aubry', 'Arthuraaubry@gmail.com', '2508329440', '02-JAN-2022', 10);

insert into Order_Sell_Renew values ('1', '15-OCT-2014', 320.04, '10004', '203');
insert into Order_Sell_Renew values ('2', '13-OCT-2019', 59.23, '10003', '203');
insert into Order_Sell_Renew values ('3', '08-OCT-2019', 1350.08, '10004', '202');
insert into Order_Sell_Renew values ('4', '17-OCT-2019', 100, '10001', '201');
insert into Order_Sell_Renew values ('5', '19-OCT-2019', 0, '10001', '201');
insert into Order_Sell_Renew values ('6', '19-OCT-2019', 20.00, '10002', '201');
insert into Order_Sell_Renew values ('7', '19-OCT-2019', 0, '10003', '201');
insert into Order_Sell_Renew values ('8', '19-OCT-2019', 666.56, '10004', '201');
insert into Order_Sell_Renew values ('9', '19-OCT-2019', 777.56, '10005', '201');
insert into Order_Sell_Renew values ('10', '19-OCT-2019', 3220.56, '10001', '202');
insert into Order_Sell_Renew values ('11', '19-OCT-2019', 20.00, '10002', '202');
insert into Order_Sell_Renew values ('12', '19-OCT-2019', 200.56, '10003', '202');
insert into Order_Sell_Renew values ('13', '19-OCT-2019', 2000, '10004', '202');
insert into Order_Sell_Renew values ('14', '19-OCT-2019', 3777.56, '10005', '202');
insert into Order_Sell_Renew values ('15', '19-OCT-2019', 0, '10001', '203');
insert into Order_Sell_Renew values ('16', '19-OCT-2019', 3444.56, '10002', '203');
insert into Order_Sell_Renew values ('17', '19-OCT-2019', 3555.56, '10003', '203');
insert into Order_Sell_Renew values ('18', '19-OCT-2019', 3666.56, '10004', '203');
insert into Order_Sell_Renew values ('19', '19-OCT-2019', 3777.56, '10005', '203');


insert into Includes values ('11111111', '1');
insert into Includes values ('11111112', '4');
insert into Includes values ('22222221', '2');
insert into Includes values ('22222222', '3');
insert into Includes values ('22222224', '5');

insert into AssessmentResult_Assess values ('10001', '13-JAN-2021', 67.20, 168.00, 23.00, 11.50, 34.30);
insert into AssessmentResult_Assess values ('10002', '21-MAY-2021', 70.90, 173.00, 23.70, 11.90, 33.50);
insert into AssessmentResult_Assess values ('10003', '04-JULY-2021', 84.90, 179.00, 26.50, 22.30, 37.80);
insert into AssessmentResult_Assess values ('10004', '08-MAY-2021', 55.87, 162.17, 21.28, 22.50, 23.07);
insert into AssessmentResult_Assess values ('10004', '08-JUL-2021', 52.00, 162.20, 19.8, 25.7, 23);
insert into AssessmentResult_Assess values ('10005', '23-JULY-2021',  0, 0, 0, 0, 0);

insert into Equipment_Deposit values ('YogaMat', 10.00);
insert into Equipment_Deposit values ('Dumbbel', 10.00);
insert into Equipment_Deposit values ('JumpRope', 5.00);
insert into Equipment_Deposit values ('Kettlebell', 20.00);
insert into Equipment_Deposit values ('SitUpsAssistant', 5.00);
insert into Equipment_Deposit values ('RollerWheel', 5.55);

insert into Equipment_Inventory values ('1', 'YogaMat', 'YES');
insert into Equipment_Inventory values ('1', 'Dumbbel', 'NO');
insert into Equipment_Inventory values ('2', 'Dumbbel', 'NO');
insert into Equipment_Inventory values ('1', 'JumpRope', 'NO');
insert into Equipment_Inventory values ('2', 'JumpRope', 'NO');
insert into Equipment_Inventory values ('3', 'JumpRope', 'NO');
insert into Equipment_Inventory values ('1', 'Kettlebell', 'NO');
insert into Equipment_Inventory values ('2', 'Kettlebell', 'NO');
insert into Equipment_Inventory values ('1', 'SitUpsAssistant', 'YES');
insert into Equipment_Inventory values ('1', 'RollerWheel', 'YES');
insert into Equipment_Inventory values ('2', 'RollerWheel', 'YES');

insert into Train values (15, '13-JAN-2021', '10001', '105');
insert into Train values (15, '13-JAN-2021', '10003', '105');
insert into Train values (19, '13-JAN-2021', '10003', '101');
insert into Train values (15, '20-JAN-2021', '10004', '104');
insert into Train values (19, '13-JAN-2021', '10005', '102');

insert into PersonalTrainerCertification values ('NASM');
insert into PersonalTrainerCertification values ('ACE');
insert into PersonalTrainerCertification values ('FitnessWorld');
insert into PersonalTrainerCertification values ('AcademyofPersonal');
insert into PersonalTrainerCertification values ('glpti');

insert into Certify values ('NASM', '101', '10-JAN-2017');
insert into Certify values ('ACE', '102', '10-JAN-2017');
insert into Certify values ('NASM', '103', '03-JAN-2021');
insert into Certify values ('NASM', '104', '12-JAN-2017');
insert into Certify values ('NASM', '105', '10-JAN-2017');
insert into Certify values ('FitnessWorld', '101', '03-JAN-2020');
insert into Certify values ('AcademyofPersonal', '104', '20-OCT-2020');
insert into Certify values ('glpti', '105', '10-JAN-2017');

insert into GroupLesson_ActivityDuration values ('Yoga', 90);
insert into GroupLesson_ActivityDuration values ('Cycle', 45);
insert into GroupLesson_ActivityDuration values ('Lift', 45);
insert into GroupLesson_ActivityDuration values ('BoxingBootcamp', 45);
insert into GroupLesson_ActivityDuration values ('Core', 45);

insert into GroupLesson_Details values ('17:00:00', '10-JAN-2020', 'Yoga', 20, '101');
insert into GroupLesson_Details values ('17:00:00', '15-JAN-2020', 'Yoga', 15, '101');
insert into GroupLesson_Details values ('18:30:00', '10-JAN-2020', 'Cycle', 3, '104');
insert into GroupLesson_Details values ('17:00:00', '15-JAN-2020', 'Lift', 15, '102');
insert into GroupLesson_Details values ('17:00:00', '17-JAN-2017', 'BoxingBootcamp', 20, '103');
insert into GroupLesson_Details values ('17:00:00', '18-JAN-2020', 'Core', 20, '105');

-- -- All sql used in our sample
-- -- insert
-- insert into Product values ('10000020');
-- insert into Membership values ('10000020', 100.00, 50);

-- -- delete
-- delete from Includes where productid='22222222';
-- delete from PersonalTrainingPackage where productid='22222222';
-- delete from Product where productid='22222222';

-- -- update
-- UPDATE Membership SET MonthlyFee= 1000 WHERE ProductID= 11111111;
-- UPDATE Membership SET NumOFMonths= 1000 WHERE ProductID= 11111111;

-- -- select
-- SELECT FirstName, MembershipExpireDate
-- FROM Customer
-- WHERE FirstName = 'Amelia' AND LastName = 'Nelly';

-- SELECT StaffID, FirstName, LastName
-- FROM Staff
-- WHERE Email = 'Alexmmark@gmail.com' AND PhoneNumber = '5524442345';
 
-- SELECT AA_Date, Weight, Height, BMI, BodyFatPct, MuscleMass
-- FROM AssessmentResult_Assess
-- WHERE CustomerID = '10004';
 
-- -- projection
-- SELECT DISTINCT C.CUSTOMERID, S.STAFFID FROM TRAIN T, TRAINER S, CUSTOMER C WHERE S.STAFFID>102 AND T.TIMESLOT>3 AND T.TRAIN_DATE=TO_DATE('2021-01-13','YYYY-MM-DD');

-- -- join
-- SELECT distinct FirstName, LastName, OrderNo
-- FROM Order_Sell_Renew, Customer
-- WHERE Customer.CustomerID = Order_Sell_Renew.CustomerID AND Order_Sell_Renew.StaffID = 201;

-- -- aggregation with groupby
-- SELECT C.LASTNAME, COUNT(C.CUSTOMERID) FROM Train T, Trainer S, Customer C WHERE S.STAFFID=T.STAFFID AND C.CUSTOMERID=T.CUSTOMERID AND T.TIMESLOT=15 GROUP BY C.LASTNAME;
 
-- -- aggregation with having
-- SELECT C.LASTNAME, AVG(C.PRIVATELESSONREMAINING) FROM Train T, Trainer S, Customer C WHERE S.STAFFID=T.STAFFID AND C.CUSTOMERID=T.CUSTOMERID GROUP BY C.LASTNAME HAVING AVG(DISTINCT C.PRIVATELESSONREMAINING) > 5;

-- -- nested aggregation with groupby
-- SELECT S.STAFFID, S.FirstName, S.LastName, MAX(T.staffDateSum) AS maxDailySale
-- FROM (SELECT SUM(TotalPrice) AS staffDateSum, StaffID, OSR_DATE 
-- FROM ORDER_SELL_RENEW 
-- GROUP BY StaffID, OSR_DATE) T, Staff S
-- WHERE  T.StaffID = S.StaffID 
-- GROUP BY S.FirstName, S.LastName, S.STAFFID;

-- -- division
-- SELECT S.staffID S
-- FROM salesperson S
-- WHERE Not Exists
-- ((select C.customerID from Customer C)
--  MINUS
-- (select O.customerID from Order_Sell_Renew O  where S.staffID = O.staffID));

COMMIT WORK;