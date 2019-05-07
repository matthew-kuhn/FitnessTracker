-- MySQL dump 10.13  Distrib 8.0.15, for macos10.14 (x86_64)
--
-- Host: 127.0.0.1    Database: FitnessTracker
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Day`
--

DROP TABLE IF EXISTS `Day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Day` (
  `Date` datetime NOT NULL,
  `UserID` varchar(50) NOT NULL,
  `Weight` int(11) NOT NULL,
  `Calories` int(11) DEFAULT NULL,
  PRIMARY KEY (`Date`,`UserID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `UserID` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Day`
--

LOCK TABLES `Day` WRITE;
/*!40000 ALTER TABLE `Day` DISABLE KEYS */;
INSERT INTO `Day` VALUES ('1998-06-09 00:00:00','acseazzu',180,6000),('2019-04-29 00:00:00','mattyknight88',150,1600),('2019-04-30 00:00:00','mattyknight88',157,1500),('2019-05-01 00:00:00','mattyknight88',155,2000),('2019-05-02 00:00:00','mattyknight88',162,2300),('2019-05-03 00:00:00','mattyknight88',155,1500),('2019-05-04 00:00:00','mattyknight88',161,2250),('2019-05-05 00:00:00','mattyknight88',160,2000),('2019-05-06 00:00:00','mattyknight88',160,2200);
/*!40000 ALTER TABLE `Day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Exercise`
--

DROP TABLE IF EXISTS `Exercise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Exercise` (
  `Exercise_Name` varchar(20) NOT NULL,
  `Exercise_Desc` varchar(140) NOT NULL,
  PRIMARY KEY (`Exercise_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Exercise`
--

LOCK TABLES `Exercise` WRITE;
/*!40000 ALTER TABLE `Exercise` DISABLE KEYS */;
INSERT INTO `Exercise` VALUES ('2Arm DB swing','two arm dumbbell swing'),('Bench Press','if you don\'t know what this is then you don\'t deserve this'),('Bottom Half Squat','start from bottom and push and combine with vert jump'),('Bt ovr row','bent over row'),('DB Curls','curls for the girls'),('Face Pulls','face pull, totally know what it is'),('Ft Squat','front squat'),('Full squat','full squat on back'),('Hang power clean','power clean from hang'),('Jump Squat','30% weight of full squat'),('Lat Pldwn','lateral pull down'),('OH Tri Ex','overhead tricept extension, tri\'s for the guys'),('Plt Chop','weighted plate over shoulder from crunch'),('Pull-up','issa pullup'),('Push-up','issa pushup'),('RDL','Romanian deadlift'),('Se Calf R','seated calf raise'),('Shldr Prs','shoulder press'),('Std Calf R','standing calf raise'),('Sumo Deadlift','sumo deadlift combines with long jump');
/*!40000 ALTER TABLE `Exercise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IN_NUTPLAN`
--

DROP TABLE IF EXISTS `IN_NUTPLAN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `IN_NUTPLAN` (
  `Nut_Plan_Name` varchar(50) NOT NULL,
  `M_Name` varchar(50) NOT NULL,
  `Day` int(11) NOT NULL,
  `Meal_num` int(11) NOT NULL,
  PRIMARY KEY (`Nut_Plan_Name`,`M_Name`,`Day`,`Meal_num`),
  KEY `M_Name` (`M_Name`),
  CONSTRAINT `M_Name` FOREIGN KEY (`M_Name`) REFERENCES `Meal` (`Meal_Name`) ON UPDATE CASCADE,
  CONSTRAINT `Nut_Plan_Name` FOREIGN KEY (`Nut_Plan_Name`) REFERENCES `Nutrition Plan` (`Nutrition_Plan_Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IN_NUTPLAN`
--

LOCK TABLES `IN_NUTPLAN` WRITE;
/*!40000 ALTER TABLE `IN_NUTPLAN` DISABLE KEYS */;
INSERT INTO `IN_NUTPLAN` VALUES ('Basic Plan','Baked Potato',1,2),('Basic Plan','Broiled Tilapia',1,3),('Basic Plan','Chicken Salad Sandwich',3,3),('Basic Plan','Cinnamon French Toast With Yogurt',2,1),('Basic Plan','Italian Baked Tilapia',2,2),('Basic Plan','Kale and Eggs',1,1),('Basic Plan','Protein Bar',3,1),('Basic Plan','Tarragon Tilapia Filets',2,3),('Basic Plan','Turkey-green beans-rice bowl',3,2),('Russia','Baked Potato',1,1),('Russia','Baked Potato',1,2),('Russia','Baked Potato',1,3),('Russia','Baked Potato',2,1),('Russia','Baked Potato',2,2),('Russia','Baked Potato',2,3),('Russia','Baked Potato',3,1),('Russia','Baked Potato',3,2),('Russia','Baked Potato',3,3),('Russia','Baked Potato',4,1),('Russia','Baked Potato',4,2),('Russia','Baked Potato',4,3),('Russia','Baked Potato',5,1),('Russia','Baked Potato',5,2),('Russia','Baked Potato',5,3),('Russia','Baked Potato',6,1),('Russia','Baked Potato',6,2),('Russia','Baked Potato',6,3),('Russia','Baked Potato',7,1),('Russia','Baked Potato',7,2),('Russia','Baked Potato',7,3),('Sample Plan 1','Almond Crusted Tilapia',1,2),('Sample Plan 1','Almond Crusted Tilapia',7,3),('Sample Plan 1','Bagel Sandwich',1,1),('Sample Plan 1','Bagel Sandwich',4,1),('Sample Plan 1','Baked Salmon With Pecans and Pesto',3,2),('Sample Plan 1','Baked Salmon With Pecans and Pesto',6,2),('Sample Plan 1','Broiled Tilapia',6,3),('Sample Plan 1','Chicken Salad Sandwich',2,3),('Sample Plan 1','Chicken Salad Sandwich',3,3),('Sample Plan 1','Cinnamon French Toast With Yogurt',5,1),('Sample Plan 1','Cooked Rice',1,2),('Sample Plan 1','Cooked Rice',6,3),('Sample Plan 1','Eggs and Rice',2,1),('Sample Plan 1','Eggs and Rice',5,3),('Sample Plan 1','Eggs and Rice',6,1),('Sample Plan 1','Italian Baked Tilapia',2,2),('Sample Plan 1','Italian Baked Tilapia',5,2),('Sample Plan 1','Kale And Eggs',3,1),('Sample Plan 1','Protein Bar',7,1),('Sample Plan 1','Roasted Brussels Sprouts',2,2),('Sample Plan 1','Roasted Sweet Potato',3,3),('Sample Plan 1','Roasted Sweet Potato',7,2),('Sample Plan 1','Salmon With Rice',1,3),('Sample Plan 1','Steamed Broccoli',4,2),('Sample Plan 1','Tarragon Tilapia Filets',4,2),('Sample Plan 1','Tarragon Tilapia Filets',7,2),('Sample Plan 1','Turkey-green beans-rice bowl',4,3);
/*!40000 ALTER TABLE `IN_NUTPLAN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `In_WPLAN`
--

DROP TABLE IF EXISTS `In_WPLAN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `In_WPLAN` (
  `W_NAME` varchar(50) NOT NULL,
  `WPlan_Name` varchar(50) NOT NULL,
  `Day` int(11) NOT NULL,
  PRIMARY KEY (`W_NAME`,`WPlan_Name`,`Day`),
  KEY `WPlan_Name` (`WPlan_Name`),
  CONSTRAINT `WPlan_Name` FOREIGN KEY (`WPlan_Name`) REFERENCES `Workout Plan` (`Workout_Plan_Name`) ON UPDATE CASCADE,
  CONSTRAINT `W_Name` FOREIGN KEY (`W_NAME`) REFERENCES `Workout` (`Workout_Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `In_WPLAN`
--

LOCK TABLES `In_WPLAN` WRITE;
/*!40000 ALTER TABLE `In_WPLAN` DISABLE KEYS */;
INSERT INTO `In_WPLAN` VALUES ('Arms','Arms',1),('Arms','Arms',2),('Arms','Arms',3),('Arms','Arms',4),('Arms','Arms',5),('Arms','Arms',6),('Arms','Arms',7),('Arms','UpYeet',7),('Arms','UpYeet1',7),('Arms','UpYeet2',7),('Arms','UpYeet3',7),('MadHopsA','UpYeet',2),('MadHopsA','UpYeet1',5),('MadHopsA','UpYeet3',2),('MadHopsB','UpYeet',5),('MadHopsB','UpYeet2',2),('MadHopsB','UpYeet3',5),('MadHopsC','UpYeet1',2),('MadHopsC','UpYeet2',5),('Torso','UpYeet',3),('Torso','UpYeet1',3),('Torso','UpYeet2',3),('Torso','UpYeet3',3);
/*!40000 ALTER TABLE `In_WPLAN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ingredient`
--

DROP TABLE IF EXISTS `Ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Ingredient` (
  `Ingredient_Name` varchar(50) NOT NULL,
  `Price/Unit` decimal(4,3) NOT NULL,
  `Unit` varchar(15) NOT NULL,
  `Amount` decimal(6,2) NOT NULL,
  PRIMARY KEY (`Ingredient_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ingredient`
--

LOCK TABLES `Ingredient` WRITE;
/*!40000 ALTER TABLE `Ingredient` DISABLE KEYS */;
INSERT INTO `Ingredient` VALUES ('Almond',0.010,'Grams',28.00),('Arugala',0.620,'Oz',3.00),('Bagel',0.540,'Bagels',1.00),('Bread',0.170,'Slice',1.00),('Broccoli',0.250,'Cup',1.00),('Brussels Sprouts',0.600,'Cup',1.00),('Chicken Breast',0.190,'Oz',8.00),('Egg',0.130,'Egg',1.00),('Green Beans',0.320,'Cup',1.00),('Ground Beef',0.280,'Oz',4.00),('Ground Turkey',0.990,'Oz',4.00),('Kale',0.790,'Cup, Chopped',1.00),('Nuked potato',0.500,'lb',1.00),('Oats',0.130,'Cup',0.00),('Pecan',0.790,'Oz',1.00),('Pesto',0.070,'Oz',2.00),('Potato',0.600,'Potato',1.00),('Protein Bar',1.210,'Bar',1.00),('Rice',0.080,'Cup',0.25),('Salmon',0.700,'Oz',4.00),('Seasoning',0.050,'Tbsp',1.00),('Sweet Potato',0.002,'Grams',133.00),('Tilapia',0.870,'Oz',4.00),('Yogurt, Vanilla',0.720,'Cup',1.00);
/*!40000 ALTER TABLE `Ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Meal`
--

DROP TABLE IF EXISTS `Meal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Meal` (
  `Meal_Name` varchar(50) NOT NULL,
  `Protein` int(11) NOT NULL,
  `Fat` int(11) NOT NULL,
  `Carbs` int(11) NOT NULL,
  PRIMARY KEY (`Meal_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Meal`
--

LOCK TABLES `Meal` WRITE;
/*!40000 ALTER TABLE `Meal` DISABLE KEYS */;
INSERT INTO `Meal` VALUES ('Almond Crusted Tilapia',41,20,16),('Bagel Sandwich',28,6,48),('Baked Potato',9,1,74),('Baked Salmon With Pecans and Pesto',63,60,2),('Broiled Tilapia',68,26,1),('Chicken Salad Sandwich',47,28,65),('Cinnamon French Toast With Yogurt',25,9,18),('Cooked Rice',3,0,37),('Eggs and Rice',31,31,45),('Extra Rad',0,0,100),('Italian Baked Tilapia',118,11,21),('Kale And Eggs',30,31,6),('Protein Bar',20,5,26),('Roasted Brussels Sprouts',8,14,12),('Roasted Sweet Potato',2,0,27),('Salmon With Rice',46,3,44),('Steamed Broccoli',4,0,10),('Tarragon Tilapia Filets',90,19,1),('Turkey-green beans-rice bowl',65,27,76);
/*!40000 ALTER TABLE `Meal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nutrition Plan`
--

DROP TABLE IF EXISTS `Nutrition Plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Nutrition Plan` (
  `Nutrition_Plan_Name` varchar(50) NOT NULL,
  PRIMARY KEY (`Nutrition_Plan_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nutrition Plan`
--

LOCK TABLES `Nutrition Plan` WRITE;
/*!40000 ALTER TABLE `Nutrition Plan` DISABLE KEYS */;
INSERT INTO `Nutrition Plan` VALUES ('Basic Plan'),('Russia'),('Sample Plan 1');
/*!40000 ALTER TABLE `Nutrition Plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Recipe`
--

DROP TABLE IF EXISTS `Recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Recipe` (
  `Ingredient_Name` varchar(50) NOT NULL,
  `Meal_Name` varchar(50) NOT NULL,
  `Ingredient_Amount` int(11) NOT NULL,
  PRIMARY KEY (`Ingredient_Name`,`Meal_Name`),
  KEY `Meal_Name` (`Meal_Name`),
  CONSTRAINT `Ingredient_Name` FOREIGN KEY (`Ingredient_Name`) REFERENCES `Ingredient` (`Ingredient_Name`) ON UPDATE CASCADE,
  CONSTRAINT `Meal_Name` FOREIGN KEY (`Meal_Name`) REFERENCES `Meal` (`Meal_Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Recipe`
--

LOCK TABLES `Recipe` WRITE;
/*!40000 ALTER TABLE `Recipe` DISABLE KEYS */;
INSERT INTO `Recipe` VALUES ('Almond','Almond Crusted Tilapia',1),('Bagel','Bagel Sandwich',1),('Bread','Chicken Salad Sandwich',2),('Bread','Cinnamon French Toast With Yogurt',1),('Broccoli','Steamed Broccoli',1),('Brussels Sprouts','Roasted Brussels Sprouts',1),('Chicken Breast','Chicken Salad Sandwich',1),('Egg','Bagel Sandwich',2),('Egg','Eggs and Rice',4),('Egg','Kale and Eggs',3),('Green Beans','Turkey-green beans-rice bowl',1),('Ground Turkey','Turkey-green beans-rice bowl',2),('Kale','Kale and Eggs',1),('Nuked potato','Extra Rad',0),('Pecan','Baked Salmon With Pecans and Pesto',1),('Pesto','Baked Salmon With Pecans and Pesto',1),('Potato','Baked Potato',1),('Protein Bar','Protein Bar',1),('Rice','Cooked Rice',1),('Rice','Eggs and Rice',1),('Rice','Salmon With Rice',1),('Rice','Turkey-green beans-rice bowl',1),('Salmon','Baked Salmon With Pecans and Pesto',2),('Salmon','Salmon With Rice',2),('Seasoning','Cinnamon French Toast With Yogurt',1),('Seasoning','Italian Baked Tilapia',1),('Seasoning','Tarragon Tilapia Filets',1),('Sweet Potato','Roasted Sweet Potato',1),('Tilapia','Almond Crusted Tilapia',2),('Tilapia','Broiled Tilapia',2),('Tilapia','Italian Baked Tilapia',2),('Tilapia','Tarragon Tilapia Filets',2),('Yogurt, Vanilla','Cinnamon French Toast With Yogurt',1);
/*!40000 ALTER TABLE `Recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Session`
--

DROP TABLE IF EXISTS `Session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Session` (
  `Workout_Name` varchar(50) NOT NULL,
  `Exercise_Name` varchar(20) NOT NULL,
  `Reps` int(11) NOT NULL,
  `Sets` int(11) NOT NULL,
  PRIMARY KEY (`Workout_Name`,`Exercise_Name`),
  KEY `Exercise_Name` (`Exercise_Name`),
  CONSTRAINT `Exercise_Name` FOREIGN KEY (`Exercise_Name`) REFERENCES `Exercise` (`Exercise_Name`) ON UPDATE CASCADE,
  CONSTRAINT `Workout_Name` FOREIGN KEY (`Workout_Name`) REFERENCES `Workout` (`Workout_Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Session`
--

LOCK TABLES `Session` WRITE;
/*!40000 ALTER TABLE `Session` DISABLE KEYS */;
INSERT INTO `Session` VALUES ('Arms','DB Curls',6,4),('Arms','Face Pulls',6,4),('Arms','OH Tri Ex',6,4),('Arms','Pull-up',6,4),('Arms','Push-up',6,4),('MadHopsA','Bottom Half Squat',6,4),('MadHopsA','Hang power clean',6,4),('MadHopsA','Sumo Deadlift',6,4),('MadHopsB','2Arm DB swing',6,4),('MadHopsB','Full squat',6,4),('MadHopsB','Jump Squat',6,4),('MadHopsB','RDL',6,4),('MadHopsB','Se Calf R',6,4),('MadHopsB','Std Calf R',6,4),('MadHopsC','Ft Squat',8,4),('MadHopsC','RDL',8,4),('Torso','Bench Press',6,4),('Torso','Bt ovr row',6,4),('Torso','Lat Pldwn',6,4),('Torso','Plt Chop',6,4),('Torso','Shldr Prs',6,4);
/*!40000 ALTER TABLE `Session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `User` (
  `UserID` varchar(50) NOT NULL,
  `First_Name` varchar(20) NOT NULL,
  `Last_Name` varchar(20) NOT NULL,
  `Password` varchar(64) NOT NULL,
  `NPlan` varchar(50) DEFAULT NULL,
  `WPlan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  KEY `Wplan` (`WPlan`),
  KEY `Nplan` (`NPlan`),
  CONSTRAINT `Nplan` FOREIGN KEY (`NPlan`) REFERENCES `Nutrition Plan` (`Nutrition_Plan_Name`) ON UPDATE CASCADE,
  CONSTRAINT `Wplan` FOREIGN KEY (`WPlan`) REFERENCES `Workout Plan` (`Workout_Plan_Name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES ('acseazzu','Andrea-Cristiano','Seazzu','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','Russia','Arms'),('joelcoff','Joel','Coffman','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',NULL,NULL),('mattyknight88','Matthew','Kuhn','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8','Sample Plan 1','UpYeet'),('noahwhite15','Noah','White','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',NULL,NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Workout`
--

DROP TABLE IF EXISTS `Workout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Workout` (
  `Workout_Name` varchar(50) NOT NULL,
  `Type` varchar(20) NOT NULL,
  PRIMARY KEY (`Workout_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Workout`
--

LOCK TABLES `Workout` WRITE;
/*!40000 ALTER TABLE `Workout` DISABLE KEYS */;
INSERT INTO `Workout` VALUES ('Arms','Weighlifting'),('MadHopsA','Weighlifting'),('MadHopsB','Weighlifting'),('MadHopsC','Weighlifting'),('Torso','Weighlifting');
/*!40000 ALTER TABLE `Workout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Workout Plan`
--

DROP TABLE IF EXISTS `Workout Plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Workout Plan` (
  `Workout_Plan_Name` varchar(50) NOT NULL,
  PRIMARY KEY (`Workout_Plan_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Workout Plan`
--

LOCK TABLES `Workout Plan` WRITE;
/*!40000 ALTER TABLE `Workout Plan` DISABLE KEYS */;
INSERT INTO `Workout Plan` VALUES ('Arms'),('UpYeet'),('UpYeet1'),('UpYeet2'),('UpYeet3');
/*!40000 ALTER TABLE `Workout Plan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-07 12:15:57
