/* These statements are handy for when we make a change to the schema or function and want to redefine them */
drop table CityTax, GrossTaxableIncomes, TaxReturnStatement, OwnsHome, OwnsRental, StudentLoans, OwnsBusiness, EmployedBy, RentedHome, TaxPayers, WorkExpenses;
drop function CalcTax;

create table TaxPayers
	(TaxPayerID 	numeric(20,0) not null,
    Name 			varchar(20),
    DOB 			numeric(8,0),
    SSN				numeric(9,0),
    StreetAddress	varchar(30),
    City 			varchar(20),
    State			varchar(10),
    Gender			varchar(10),
    IsElderly		varchar(3),
    IsHandicapped	varchar(3),
    IsWoundedVet	varchar(3),
    primary key(TaxPayerID)
    );
insert into TaxPayers
values(201920392092039, "Moira Rose", 2910293, 29740293, "2020 south farthington ln", "Nantucket", "CT", "Female", "Yes", "Yes", "Yes");
set @TaxPayerID=201920392092039;

create table GrossTaxableIncomes
	(TaxPayerID				numeric(20, 0) not null,
	Amount					numeric(10, 2) not null,
    FederalTaxesWithheld	numeric(10,2),
	Type					varchar(20),
	primary key(TaxPayerID, Amount));
insert into GrossTaxableIncomes values(201920392092039, 50000, 10000, "W2");
insert into GrossTaxableIncomes values(201920392092039, 10000, 5000, "Business Income");

create table StudentLoans
	(TaxPayerID			numeric(20, 0) not null,
	Amount				numeric(10, 2),
    InterestRate		numeric(5,2),
	primary key(TaxPayerID),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID));
insert into StudentLoans values(201920392092039, 50000, 0.95);
    
create table OwnsHome
	(TaxPayerID			numeric(20, 0) not null,
	StreetAddress	varchar(30),
    City 			varchar(20),
    State			varchar(10),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, StreetAddress, City));
  insert into OwnsHome values(201920392092039, "2020 farthington ln", "Nantucket", "CT");  
  
create table OwnsRental
	(TaxPayerID			numeric(20, 0) not null,
	StreetAddress	varchar(30),
    City 			varchar(20),
    State			varchar(10),
    RepairCosts		numeric(10,2),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, StreetAddress, City));
  insert into OwnsRental values(201920392092039, "2022 farthington ln", "Nantucket", "CT", 1250.87);
 
create table WorkExpenses
	(TaxPayerID		numeric(20,0) not null,
	Description		varchar(50),
	Amount	numeric(10, 2),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
    primary key(TaxPayerID, Description, Amount));
insert into WorkExpenses values(201920392092039, "Bought candies for team", 10.00);

create table CityTax
	(City varchar(20) not null,
    State varchar(20) not null,
    Amount numeric(10,2) not null,
    primary key(City, State)
    );
insert into CityTax values("Nantucket", "CT", 1200.00);

create table OwnsBusiness
	(TaxPayerID		numeric(20,0) not null,
	BusinessName	varchar(20) not null,
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, BusinessName));
 insert into OwnsBusiness values(201920392092039, "Moira's Roses");
    
create table EmployedBy
	(TaxPayerID		numeric(20,0) not null,
	EmployerID		numeric(20,0) not null,
    EmployerName	varchar(30),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, EmployerID));
 insert into EmployedBy values(201920392092039, 01, "Rose Apothecary");
 
create table RentedHome
	(TaxPayerID		numeric(20,0) not null,
	Address 		varchar(30) not null,
    StartDate		numeric(8,0),
    EndDate			numeric(8,0),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, Address));
 insert into RentedHome values(201920392092039, "2040 farthington ln", 09102020, 10102020);
 
 create table HasDependent
	(TaxPayerID			numeric(20, 0) not null,
	DependentName		varchar(30) not null,
    DependentSSN		numeric(9,0),
    RelationshipType	varchar(50),
	primary key(TaxPayerID, DependentName),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID));
insert into HasDependent values( 201920392092039, "Alexis Rose", 736728172, "Daughter");
insert into HasDependent values( 201920392092039, "David Rose", 920938293, "Son");

/* Query to determine total taxes withheld */
set @TotalTaxesWithheld=(select sum(FederalTaxesWithheld)
from GrossTaxableIncomes
where TaxPayerID=@TaxPayerID);

/* Automatic Deductions: automatic=$3000, female/senior=$500, handicap=$750, woundedvet=$2000 */
/* To calculate this, we must sum the automatic deduction with all other possible itemized deductions */
/* See here for other things we want to deduct: https://www.policygenius.com/blog/tax-deductions-tax-credits-you-can-take/ */
set @IsWoundedVet = case /* case woundedvet='yes' then 1 case woundedvet='no' then 0 */
	when (select IsWoundedVet from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then 1
    else 0
end;
set @IsWomanOrElderly = case /* same as above, but with the gender and elderly columns in TaxPayers row */
	when (select Gender from TaxPayers where TaxPayerID=@TaxPayerID) = "Female" then "1"
    when (select IsElderly from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then "1"
    else 0
end;
set @IsHandiCap = case
	when (select IsHandicapped from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then 1
	else 0
end;

set @NumberOfDependents = (select count(*) from HasDependent where TaxPayerID=@TaxPayerID);
set @MaxDeduction= 700 + /* Base automatic deduction */
	(@IsWoundedVet*2000) /* Wounded vet deduction */
    +(@IsWomanOrElderly*500) /* Woman or elderly deduction */
    +(@IsHandicap*750) /*Handicapped deduction */
    +((select Amount from StudentLoans where TaxPayerID=@TaxPayerID)*(select InterestRate from StudentLoans where TaxPayerID=@TaxPayerID))/100
    +(select sum(Amount) from WorkExpenses where TaxPayerID=@TaxPayerID) /* Work Expenses are deductible */
    +(@NumberOfDependents*2000);

 /* We will store the final tax return info for a given taxpayer in this table */
create table TaxReturnStatement
	(TaxPayerID				numeric(20, 0) not null,
	AmountOwed				numeric(10, 2),
    RefundDue				numeric(10,2),
	primary key(TaxPayerID));
insert into TaxReturnStatement values(@TaxPayerID, 0, 0);

set @TaxableIncome=(select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID)-@MaxDeduction;

delimiter //
create function calcTax(income numeric(10,2))
    returns numeric(10,2) deterministic
    begin
        declare result numeric(10,2);
        if income > 35000 then
            set result = (income - 35000) * 0.3;
            set result = result + 17000 * 0.25;
            set result = result + 7000 * 0.2;
            set result = result + 6000 * 0.15;
            set result = result + 5000 * 0.1;
        elseif income > 18000 then
            set result = (income - 18000) * 0.25;
            set result = result + 7000 * 0.2;
            set result = result + 6000 * 0.15;
            set result = result + 5000 * 0.1;
        elseif income > 11000 then
            set result = (income - 11000) * 0.2;
            set result = result + 6000 * 0.15;
            set result = result + 5000 * 0.1;
        elseif income > 5000 then
            set result = (income - 5000) * 0.15;
            set result = result + 5000 * 0.1;
        else
            set result = income * 0.1;
        end if;
        return (result);
    end //
delimiter ;

set @TotalTax = calcTax(@TaxableIncome);

/* So, all of our variables at this point are:
	IsWoundedVet: indicates whether the taxpayer is a wounded vet or not
	IsWomanOrElderly: indicates whether the taxpayer is a woman or elderly
    IsHandicapped: indicates whether the taxpayer is handicapped
	MaxDeduction: indicates the total deductions for the taxpayer
	TaxableIncome: indicates the total amount of the taxpayer's income that is taxable
	TotalTax: indicates the amount of taxes due to the federal government
Now, we need:
	RefundDue: If the taxpayer had income witheld that exceeds TotalDax, they are due a refund
	TaxesDue: If the taxpayer has income witheld that does not exceed TotalTax, then they owe a tax payment
RefundDue and TaxesDue are the variables that we will use to generate a tax return statement. 
*/
set @TaxesWitheld = (select sum(FederalTaxesWithheld) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID);
set @RefundDue = (@TotalTax-@TaxesWitheld)*-1;
set @RefundDue = 
	case when @RefundDue < 0 then 0
    else @RefundDue
end;
select @RefundDue;

set @TaxesDue = @TotalTax - @TaxesWitheld;
set @TaxesDue = case
	when @TaxesDue < 0 then 0
    else @TaxesDue
end;

update TaxReturnStatement set AmountOwed=@TaxesDue where TaxPayerID=@TaxPayerID;
update TaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
select * from TaxReturnStatement



/* Procedure we can use with a TaxPayerID to generate tax return statement */
delimiter //
create function generateTaxReturnStatement(TID numeric(20,0))
	returns numeric(20,0) deterministic
    begin
		set @TaxPayerID=TID;
        set @IsWoundedVet = case /* case woundedvet='yes' then 1 case woundedvet='no' then 0 */
			when (select IsWoundedVet from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then 1
			else 0
		end;
		set @IsWomanOrElderly = case /* same as above, but with the gender and elderly columns in TaxPayers row */
			when (select Gender from TaxPayers where TaxPayerID=@TaxPayerID) = "Female" then "1"
			when (select IsElderly from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then "1"
		else 0
		end;
		set @IsHandiCap = case
			when (select IsHandicapped from TaxPayers where TaxPayerID=@TaxPayerID) = "Yes" then 1
			else 0
		end;
        
        set @NumberOfDependents = (select count(*) from HasDependent where TaxPayerID=@TaxPayerID);
        
        set @MaxDeduction= 700 + /* Base automatic deduction */
			(@IsWoundedVet*2000) /* Wounded vet deduction */
			+(@IsWomanOrElderly*500) /* Woman or elderly deduction */
			+(@IsHandicap*750) /*Handicapped deduction */
			+((select Amount from StudentLoans where TaxPayerID=@TaxPayerID)*(select InterestRate from StudentLoans where TaxPayerID=@TaxPayerID))/100
			+(select sum(Amount) from WorkExpenses where TaxPayerID=@TaxPayerID) /* Work Expenses are deductible */
            +(@NumberOfDependents*2000);
           
	set @TaxableIncome=(select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID)-@MaxDeduction;
		set @TotalTax = calcTax(@TaxableIncome);
        set @TaxesWitheld = (select sum(FederalTaxesWithheld) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID);
		set @RefundDue = (@TotalTax-@TaxesWitheld)*-1;
		set @RefundDue = 
			case when @RefundDue < 0 then 0
			else @RefundDue
		end;
		set @TaxesDue = @TotalTax - @TaxesWitheld;
		set @TaxesDue = case
			when @TaxesDue < 0 then 0
			else @TaxesDue
		end;
        select @TaxableIncome, @MaxDeduction, @TotalTax, @TaxesDue, @TaxesWitheld, @RefundDue;
        update TaxReturnStatement set AmountOwed=@TaxesDue where TaxPayerID=@TaxPayerID;
		update TaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
        
        update CompleteTaxReturnStatement set NumberOfDependents=@NumberOfDependents where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set TotalIncome=@TaxableIncome where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set TaxesWitheld=@TaxesWitheld where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set PaymentDue=@TaxesDue where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set MaxDeduction=@MaxDeduction where TaxPayerID=@TaxPayerID;
return(TID);
        /*return (result);*/
end //
delimiter ;

select @NumberOfDependents;
update TaxReturnStatement set RefundDue=0 where TaxPayerID=201920392092039;
set @test = generateTaxReturnStatement(201920392092039);
select * from TaxReturnStatement;

/* This table will be the one that we ultmiately query from the web interface to read out the values that we need as input for the pdf */
create table CompleteTaxReturnStatement
	(TaxPayerID			numeric(20,0) not null,
	Name 				varchar(20),
    DOB 				numeric(8,0),
    SSN					numeric(9,0),
    StreetAddress		varchar(30),
    City 				varchar(20),
    State				varchar(10),
    NumberOfDependents	numeric(3,0),
    TotalIncome			numeric(10,0),
    MaxDeduction		numeric(10,0),
    TaxableIncome		numeric(10,0),
    TaxesWitheld		numeric(10,0),
    PaymentDue			numeric(10,0),
    RefundDue			numeric(10,2),
    primary key(TaxPayerID));
insert into CompleteTaxReturnStatement
values( @TaxPayerID, @TaxPayerName, @DOB, @SSN, @Address, @City, @State, @NumberOfDependents, @TotalIncome, @MaxDeduction, @TaxableIncome, @TaxesWitheld, @TaxesDue, @RefundDue);

/* a lot of the variables for that insert statement aren't initialized yet...let's do that here */
set @TaxPayerID = 201920392092039;
set @TaxPayerName = (select Name from TaxPayers where TaxPayerID=@TaxPayerID);
set @DOB = (select DOB from TaxPayers where TaxPayerID=@TaxPayerID);
set @SSN = (select SSN from TaxPayers where TaxPayerID=@TaxPayerID);
set @Address= (select StreetAddress from TaxPayers where TaxPayerID=@TaxPayerID);
set @City = (select City from TaxPayers where TaxPayerID=@TaxPayerID);
set @State = (select State from TaxPayers where TaxPayerID=@TaxPayerID);
set @NumberOfDependents = (select count(*) from HasDependent where TaxPayerID=@TaxPayerID);
set @TotalIncome = (select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID);
/* The remaining variables can be set by calling the generateTaxReturnStatement function */
    select * from CompleteTaxReturnStatement
    
    