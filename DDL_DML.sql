-- UPDATE DDL
-- Added line 23 (constraints)
-- Updated all reservations (lines 88 - 111)
-- Added lines 209 - 237 
-- Added lines 254 - 269

USE hotel;

drop table IF EXISTS HotelManagerAccount;
drop table IF EXISTS HotelBranch_Manages;
drop table IF EXISTS FinalBill;
drop table IF EXISTS Reservation;
drop table IF EXISTS ClientAccount;
drop table IF EXISTS Can_Access;
drop table IF EXISTS Room;
drop table IF EXISTS Regular;
drop table IF EXISTS Deluxe;

create table ClientAccount
( client_id INT not null AUTO_INCREMENT,
  username varchar(25) not null,
  cl_lname varchar(25) not null,
  cl_fname varchar(25) not null,
  credit_card integer not null,
  password varchar(25) not null,
  registration_date date not null,
  email varchar(50),
  phone_number varchar(25),
PRIMARY KEY(client_id)
) ENGINE=INNODB;

create table HotelBranch
( branch_no integer not null PRIMARY KEY,
  branch_addr varchar(50) not null,
  branch_name varchar(20) not null,
  branch_city varchar(20) not null
) ENGINE=INNODB;

create table HotelManagerAccount
( manager_id integer not null AUTO_INCREMENT,
  username varchar(25) not null,
  branch_no integer not null,
  password varchar(25) not null,
  lname varchar(25) not null,
  fname varchar(25) not null,
  
  PRIMARY KEY (manager_id),
  
  FOREIGN KEY (branch_no) REFERENCES HotelBranch(branch_no) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB

CREATE TABLE Manages(
manager_id integer NOT NULL ,
branch_no integer NOT NULL ,
PRIMARY KEY ( manager_id, branch_no )
) ENGINE = INNODB

create table Room
(price_rate integer not null,
room_id integer not null,
branch_no integer not null,
room_type varchar(20) not null,
PRIMARY KEY (room_id, branch_no),
FOREIGN KEY (branch_no) REFERENCES HotelBranch(branch_no) ON UPDATE CASCADE ON DELETE RESTRICT,
FOREIGN KEY (room_type) REFERENCES Room_Type(room_type) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = INNODB;

create table Room_Type
(
room_type varchar(20) not null PRIMARY KEY
) ENGINE = INNODB;

create table Reservation
( reservation_id integer not null AUTO_INCREMENT PRIMARY KEY,
  client_id integer not null,
  room_id integer not null,
  branch_no integer not null,
  start_date date not null,
  end_date date not null,
  FOREIGN KEY (client_id) references ClientAccount(client_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (room_id, branch_no) references Room(room_id, branch_no) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE =  INNODB;

create table FinalBill
( bill_id integer not null PRIMARY KEY,
  client_id integer not null,
  reservation_id integer not null,
  is_paid char(1) not null check (is_paid in ('Y', 'N')),
  FOREIGN KEY (client_id) references ClientAccount(client_id) on update cascade on delete restrict,
  FOREIGN KEY (reservation_id) references Reservation(reservation_id) on update cascade on delete restrict
 ) ENGINE = INNODB;

-- converted string CCs to INTs; added one digit to each int to meet constraints

insert into ClientAccount values
(1, 'Winger', 'Jeff', 13782822463100057, '1a2s3d4f', null , 'West 22nd Avenue, Vancouver, British Columbia');

insert into ClientAccount values
(2, 'MacHale', 'Ethel', 6363822323103254 , '9171986', null , 'East 15th Avenue / Quebec Street');

insert into ClientAccount values
(3, 'Wakamatsu', 'Greg', 7638222453109095, 'hotel!password!', '604-345-6789', null);

insert into ClientAccount (username, cl_lname, cl_fname, credit_card, password, email, phone_number) values 
('russianvixen', 'Mironova', 'Michelle', 5638809455673113, 'stillmotif', 'm_and_m@hotmail.com' , null), 
('the_unabomber', 'Laak', 'Philip', 2363843332321253, 'aceontheriver', 'phillycheese@hotmail.com', '702-734-7777');,
('relativity', 'Weinstein', 'Gilbert', 8323843249356001, 'e=mc2', 'weinstein@gmail.com', '778-756-7289'),
('newscaster' , 'MacHale', 'Ethel', 6363822323103254 , '9171986', 'ethel.machale@acnnews.com', null);

insert into ClientAccount values
(6, 'Stone', 'Corey', 9363843333356270, 'fromragsto$$$$$', '503-999-8632', '4897 Manor Street, Vancouver, British Columbia');

insert into ClientAccount values
(8, 'Smith', 'Michael', 8562058777084389, '123456789', '368-246-4742', '2874 Granville Street, Vancouver, BC');

insert into ClientAccount values
(9, 'Johnson', 'Christopher', 1632810097052561, 'notnull', '934-582-9347', '8436 Oak Street, Vancouver, Bc');

insert into ClientAccount values
(10, 'Williams', 'Matthew', 6486438636833498, 'something', '123-456-7890', '7235 Cambie Street');

insert into ClientAccount values
(11, 'Jones', 'Lee', 2630227980421749, 'qwerty', '111-111-1111', '9236 Main Street, Vancouver');

insert into ClientAccount values
(12, 'Brown', 'Joshua', 8250154721356720, 'hahaha', '222-222-2222', '362 Rue du Conseil, Sherbrooke, Quebec');

insert into ClientAccount values
(13, 'Kim', 'Tyler', 5140570291568210, 'agsd9f7gh', '333-333-3333', null);

insert into ClientAccount values
(14, 'Nguyen', 'Alexander', 2655640578992050, '983hwqg', '604-435-7244', null);

insert into ClientAccount values
(15, 'Davis', 'Brandon', 2042678665315980, 'sdfbq', '273-635-9324', null);

insert into ClientAccount values
(16, 'Miller', 'Daniel', 2072791589344680, 'qqqber', null, null);

insert into ClientAccount values
(17, 'Liu', 'Nicholas', 4006198274559800, '743qhg', null, null);

insert into ClientAccount values
(18, 'Wilson', 'Jacob', 2082484383610000, 'imjust', null, null);

insert into ClientAccount values
(19, 'Moore', 'Jessica', 1302598761128140, 'typing', null, null);

insert into ClientAccount values
(20, 'Baines', 'Harvey', 5356247968240910, 'randomly', null, null);

insert into ClientAccount values
(21, 'Taylor', 'Sarah', 2649454539703060, 'dfuih4', null, '3487 Fraser Street, Vancouver BC');

insert into ClientAccount values
(22, 'Anderson', 'Ashley', 4345450301094630, 'shd8g4rq', null, '2739 Knight Street, Vancouver');

insert into ClientAccount values
(23, 'Lam', 'Christina', 6126176502630180, 'q4hfh5', '604-374-9927', null);

insert into ClientAccount values
(24, 'Colbert', 'Stephen', 9546852038265640, '46hwth633k', '212-301-5296', '513 West 54th Street, New York');

insert into ClientAccount values
(25, 'Stukov', 'Alexei', 5587201752840628, 'thereisnocowlevel', '250-238-0325', 'Braxis');

insert into ClientAccount values
(26, 'Langston', 'Elliott', 1854124821190750, 'notmyrealcreditcard', '604-836-7244', '2637 Elliott Street, Vancouver, BC');

username, cl_lname, cl_fname. credit_card, password, email, phone_number
-- End edits to ClientAccounts

insert into HotelBranch values
( 01, '1038 Canada Place', 'Pacific Rim Hotel', 'Vancouver');

insert into HotelBranch values
( 02, '900 Canada Pl', 'Waterfront Hotel', 'Vancouver');

insert into HotelBranch values
( 03, '900 W Georgia St', 'Downtown', 'Vancouver');

insert into HotelBranch values
( 04, '111 Robson St', 'Robson', 'Vancouver');

insert into HotelBranch values
( 05, '1088 Burrard St', 'Burrard', 'Vancouver');

insert into HotelManagerAccount values
(5001, 01, 'hamlet505', 'Adama', 'Lee');

insert into HotelManagerAccount values
(5002, 02, 'romeo221', 'Baltar', 'Gaius');

insert into HotelManagerAccount values
(5003, 03, 'verona513', 'Roslin', 'Laura');

insert into HotelManagerAccount values
(5004, 04, 'cylon422', 'Tyrol', 'Galen');

insert into HotelManagerAccount values
(5005, 04, 'starbuck1', 'Karl', 'Agathon');

insert into Can_Access values
(1, 5001, 1); 

insert into Can_Access values
(2, 5001, 6); 

insert into Can_Access values
(3, 5002, 2);

insert into Can_Access values
(4, 5003, 3);

insert into Can_Access values
(5, 5003, 7);

insert into Can_Access values
(6, 5004, 4);

insert into Room values
(50, 001, 01, 'Regular');

insert into Room values
(100, 002, 01, 'Regular');

insert into Room values
(50, 003, 02, 'Regular');

insert into Room values
(150, 004, 02, 'Regular');

insert into Room values
(60, 005, 03, 'Regular');

insert into Room values
(200, 006, 03, 'Deluxe');

insert into Room values
(75, 007, 04, 'Deluxe');

insert into Room values
(75, 008, 04, 'Regular');

insert into Room values
(200, 009, 05, 'Regular');

insert into Room values
(200, 010, 05, 'Deluxe');

insert into Room values
(200, 011, 05, 'Regular');

insert into Room values
(200, 012, 05, 'Regular');

insert into Room values
(125, 013, 04, 'Deluxe');

insert into Room values
(125, 014, 04, 'Deluxe');

insert into Room values
(175, 015, 04, 'Deluxe');

insert into Room values
(175, 016, 04, 'Regular');

insert into Room values
(60, 017, 03, 'Regular');

insert into Room values
(60, 018, 03, 'Regular');

insert into Room values
(110, 019, 03, 'Deluxe');

insert into Room values
(140, 020, 03, 'Deluxe');

insert into Room values
(110, 021, 03, 'Regular');

insert into Room values
(140, 022, 03, 'Deluxe');

insert into Room values
(150, 023, 02, 'Regular');

insert into Room values
(80, 024, 02, 'Deluxe');

insert into Room values
(80, 025, 02, 'Regular');

insert into Room values
(90, 026, 02, 'Deluxe');

insert into Room values
(50, 027, 01, 'Regular');

insert into Room values
(60, 028, 01, 'Deluxe');

insert into Room values
(155, 029, 01, 'Regular');

insert into Room values
(104, 40, 030, 01, 'Deluxe');

insert into Room values
(100, 031, 01, 'Deluxe');

insert into Room values
(110, 032, 01, 'Regular');

insert into Room values
(125, 033, 01, 'Regular');

insert into Room values
(225, 034, 01, 'Deluxe');

insert into Room values
(250, 035, 01, 'Deluxe');

insert into Room values
(300, 036, 01, 'Regular');

insert into Room values
(400, 037, 01, 'Deluxe');

insert into Room values
(500, 038, 01, 'Regular');

insert into Room values
(700, 039, 01, 'Regular');

insert into Reservation values
( 02, 01, 01, 01, '2014-01-01', '2014-01-05');

insert into Reservation values
( 02, 01, 03, 02, '2014-05-20', '2014-05-26');

insert into Reservation values
( 03, 02, 02, 01, '2014-04-01', '2014-04-02');

insert into Reservation values
( 04, 01, 01, 01, '2014-09-25', '2014-10-13');

insert into Reservation values
( 05, 02, 03, 02, '2014-02-01', '2014-02-15');

insert into Reservation values
( 15, 06, 03, 02, '2014-11-22', '2014-11-23');

insert into Reservation values
( 16, 07, 04, 02, '2014-11-25', '2014-11-30');

insert into Reservation values
( 17, 08, 10, 05, '2014-11-26', '2014-11-28');

insert into Reservation values
( 18, 09, 11, 05, '2014-11-27', '2014-11-29');

insert into Reservation values
( 19, 10, 11, 05, '2014-11-22', '2014-12-03');

insert into Reservation values
( 20, 11, 12, 05, '2014-11-28', '2014-12-03');

insert into Reservation values
( 21, 12, 13, 04, '2014-11-29', '2014-11-30');

insert into Reservation values
( 22, 13, 14, 04, '2014-11-30', '2014-12-01');

insert into Reservation values
( 23, 13, 14, 04, '2014-12-23', '2014-12-29');

insert into Reservation values
( 24, 14, 15, 04, '2014-12-05', '2014-12-08');

insert into Reservation values
( 25, 15, 17, 03, '2014-12-09', '2014-12-11');

insert into Reservation values
( 26, 16, 18, 03, '2014-12-17', '2014-12-22');

insert into Reservation values
( 27, 17, 19, 03, '2014-12-23', '2014-12-26');

insert into Reservation values
( 28, 18, 20, 03, '2014-12-24', '2014-12-26');

insert into Reservation values
( 29, 19, 20, 03, '2014-01-01', '2014-01-04');

insert into Reservation values
( 30, 20, 23, 02, '2014-01-05', '2014-01-18');

insert into Reservation values
( 31, 21, 23, 02, '2014-01-19', '2014-02-16');

insert into Reservation values
( 32, 22, 24, 02, '2014-12-01', '2014-12-14');

insert into Reservation values
( 33, 23, 25, 02, '2014-12-02', '2014-12-07');

insert into Reservation values
( 34, 23, 27, 01, '2014-12-07', '2014-12-09');

insert into Reservation values
( 35, 23, 28, 01, '2014-12-10', '2014-12-13');

insert into Reservation values
( 36, 23, 29, 01, '2014-12-13', '2014-12-14');

insert into Reservation values
( 37, 23, 28, 01, '2014-12-16', '2014-12-21');

insert into Reservation values
( 38, 23, 31, 01, '2014-12-23', '2014-12-29');

insert into Reservation values
( 39, 23, 32, 01, '2014-12-29', '2014-01-03');

insert into Reservation values
( 40, 23, 33, 01, '2014-01-03', '2014-01-22');

insert into Reservation values
( 41, 23, 34, 01, '2014-01-16', '2014-01-25');

insert into Reservation values
( 42, 23, 35, 01, '2014-01-24', '2014-07-03');

insert into Reservation values
( 43, 24, 36, 01, '2014-11-28', '2014-12-02');

-- start division insertions
insert into Reservation values
( 06, 03, 01, 01, '2014-01-01', '2014-01-15');

insert into Reservation values
( 07, 03, 03, 02, '2014-01-16', '2014-01-17');

insert into Reservation values
( 08, 03, 05, 03, '2014-02-16', '2014-02-17');

insert into Reservation values
( 09, 03, 07, 04, '2014-03-16', '2014-03-17');

insert into Reservation values
( 10, 03, 09, 05, '2014-04-16', '2014-04-17');

insert into Reservation values
( 11, 04, 01, 01, '2014-01-16', '2014-01-17');

insert into Reservation values
( 12, 04, 03, 02, '2014-01-18', '2014-01-19');

insert into Reservation values
( 13, 04, 05, 03, '2014-02-18', '2014-02-19');

insert into Reservation values
( 14, 04, 07, 04, '2014-03-18', '2014-03-20');

-- end division insertions

insert into FinalBill values
( 01, 01, 01, 'N');

insert into FinalBill values
( 02, 01, 02, 'Y');

insert into FinalBill values
( 03, 02, 03, 'Y');

insert into FinalBill values
( 04, 04, 04, 'Y');

insert into FinalBill values
( 05, 04, 05, 'N');

-- start division insertions
insert into FinalBill values
( 06, 03, 06, 'Y');

insert into FinalBill values
( 07, 03, 07, 'Y');

insert into FinalBill values
( 08, 03, 08, 'Y');

insert into FinalBill values
( 09, 03, 09, 'Y');

insert into FinalBill values
( 10, 03, 10, 'Y');
-- end division insertions

insert into Regular values
(001, 01);

insert into Regular values
(003, 02);

insert into Regular values
(005, 03);

insert into Regular values
(007, 04);

insert into Regular values
(008, 04);

insert into Deluxe values
(002, 01);

insert into Deluxe values
(004, 02);

insert into Deluxe values
(006, 03);

insert into Deluxe values
(009, 05);

insert into Deluxe values
(010, 05);

insert into Deluxe values
(011, 05);

insert into Deluxe values
(012, 05);

insert into Regular values
(013, 04);

insert into Regular values
(014, 04);

insert into Deluxe values
(015, 04);

insert into Deluxe values
(016, 04);

insert into Regular values
(017, 03);

insert into Regular values
(018, 03);

insert into Regular values
(019, 03);

insert into Regular values
(020, 03);

insert into Regular values
(021, 03);

insert into Regular values
(022, 03);

insert into Deluxe values
(023, 02);

insert into Regular values
(024, 02);

insert into Regular values
(025, 02);

insert into Regular values
(026, 02);

insert into Regular values
(027, 01);

insert into Regular values
(028, 01);

insert into Regular values
(029, 01);

insert into Regular values
(030, 01);

insert into Regular values
(031, 01);

insert into Regular values
(032, 01);

insert into Regular values
(033, 01);

insert into Deluxe values
(034, 01);

insert into Deluxe values
(035, 01);

insert into Deluxe values
(036, 01);

insert into Deluxe values
(037, 01);

insert into Deluxe values
(038, 01);

insert into Deluxe values
(039, 01);