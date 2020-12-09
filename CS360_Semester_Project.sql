/* These drop statements are for when we make a change to the scheme or the functions and need to re-declare them */
drop table CityTax, GrossTaxableIncomes, TaxReturnStatement, OwnsHome, OwnsRental, StudentLoans, OwnsBusiness, EmployedBy, RentedHome, TaxPayers, WorkExpenses;
drop function CalcTax;
drop function generateTaxReturnStatement;

/*----------CREATE TABLE STATEMENTS------------------*/
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

create table MedicalExpenses
	(TaxPayerID		numeric(20,0) not null,
	Description		varchar(50),
	Amount	numeric(10, 2),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
    primary key(TaxPayerID, Description, Amount));
    
create table CharitableContributions
	(TaxPayerID		numeric(20,0) not null,
	Description		varchar(50),
	Amount	numeric(10, 2),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
    primary key(TaxPayerID, Description, Amount));
    
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
    
create table TaxReturnStatement
	(TaxPayerID				numeric(20, 0) not null,
	AmountOwed				numeric(10, 2),
    RefundDue				numeric(10,2),
	primary key(TaxPayerID));

/*--------------------FUNCTIONS----------------------*/

/* The following function accepts a TaxPayerID as input, calculates the values relevant to that person's tax return statement, and stores them in the CompleteTaxReturnStatement table */
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
        set @CityTaxOwed = (select Amount from CityTax where City=(select City from TaxPayers where TaxPayerID=@TaxPayerId));
        
		set @WorkExpenses = (select sum(Amount) from WorkExpenses where TaxPayerID=@TaxPayerID);
        set @WorkExpenses = case
			when @WorkExpenses > 0 then @WorkExpenses
            else 0
		end;
        
        set @CharitableContributions = (select sum(Amount) from CharitableContributions where TaxPayerID=@TaxPayerID);
        set @CharitableContributions = case
			when @CharitableContributions > 0 then @CharitableContributions
            else 0
		end;
        
        set @MedicalExpenses = (select sum(Amount) from MedicalExpenses where TaxPayerID=@TaxPayerID);
        set @MedicalExpenses = case
			when @MedicalExpenses > 0 then @MedicalExpenses
            else 0
		end;
        
        set @StudentLoanInterest = ((select Amount from StudentLoans where TaxPayerID=@TaxPayerID)*(select InterestRate from StudentLoans where TaxPayerID=@TaxPayerID))/100;
        set @StudentLoanInterest = case
			when @StudentLoanInterest > 0 then @StudentLoanInterest
            else 0
		end;
        set @MaxDeduction= 700 + /* Base automatic deduction */
			(@IsWoundedVet*2000) /* Wounded vet deduction */
			+(@IsWomanOrElderly*500) /* Woman or elderly deduction */
			+(@IsHandicap*750) /*Handicapped deduction */
			+@StudentLoanInterest
			+@WorkExpenses /* Work Expenses are deductible */
            +(@NumberOfDependents*2000)
            +@CharitableContributions
            +@MedicalExpenses;
         
        set @TotalIncome=(select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID); 
		set @TaxableIncome=(select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID)-@MaxDeduction;
		set @TotalTax = calcTax(@TaxableIncome) + @CityTaxOwed;
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
        update TaxReturnStatement set AmountOwed=@TaxesDue where TaxPayerID=@TaxPayerID;
		update TaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
        
        update CompleteTaxReturnStatement set NumberOfDependents=@NumberOfDependents where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set TotalIncome=@TotalIncome where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set TaxesWitheld=@TaxesWitheld where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set PaymentDue=@TaxesDue where TaxPayerID=@TaxPayerID;
        update CompleteTaxReturnStatement set MaxDeduction=@MaxDeduction where TaxPayerID=@TaxPayerID;
return(TID);
        /*return (result);*/
end //
delimiter ;

/* This code is for testing the variables involved in calculating the values for the tax return statement */
set @TaxPayerID = 201920392092039;
set @TaxPayerName = (select Name from TaxPayers where TaxPayerID=@TaxPayerID);
set @DOB = (select DOB from TaxPayers where TaxPayerID=@TaxPayerID);
set @SSN = (select SSN from TaxPayers where TaxPayerID=@TaxPayerID);
set @Address= (select StreetAddress from TaxPayers where TaxPayerID=@TaxPayerID);
set @City = (select City from TaxPayers where TaxPayerID=@TaxPayerID);
set @State = (select State from TaxPayers where TaxPayerID=@TaxPayerID);
set @NumberOfDependents = (select count(*) from HasDependent where TaxPayerID=@TaxPayerID);
set @TotalIncome = (select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID);
    
/* The following function accepts an income amount as input and determines the amount of taxes that are owed on that amount of income, according to the gradient tax scale */    
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

select @NumberOfDependents;
update TaxReturnStatement set RefundDue=0 where TaxPayerID=201920392092039;
set @test = generateTaxReturnStatement(201920392092039);
select * from CompleteTaxReturnStatement;
    