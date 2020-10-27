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
    primary key(TaxPayerID));
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

set @MaxDeduction= 700 + /* Base automatic deduction */
	(@IsWoundedVet*2000) /* Wounded vet deduction */
    +(@IsWomanOrElderly*500) /* Woman or elderly deduction */
    +(@IsHandicap*750) /*Handicapped deduction */
    +((select Amount from StudentLoans where TaxPayerID=@TaxPayerID)*(select InterestRate from StudentLoans where TaxPayerID=@TaxPayerID))/100
    +(select sum(Amount) from WorkExpenses where TaxPayerID=@TaxPayerID); /* Work Expenses are deductible */

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
create procedure generateTaxReturnStatement @IDNo numeric(20,0) 
	as
    begin
		set @TaxPayerID=@IDNo;
        set @MaxDeduction= 700 + /* Base automatic deduction */
			(@IsWoundedVet*2000) /* Wounded vet deduction */
			+(@IsWomanOrElderly*500) /* Woman or elderly deduction */
			+(@IsHandicap*750) /*Handicapped deduction */
			+((select Amount from StudentLoans where TaxPayerID=@TaxPayerID)*(select InterestRate from StudentLoans where TaxPayerID=@TaxPayerID))/100
			+(select sum(Amount) from WorkExpenses where TaxPayerID=@TaxPayerID); /* Work Expenses are deductible */
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
        update TaxReturnStatement set AmountOwed=@TaxesDue where TaxPayerID=@TaxPayerID;
		update TaxReturnStatement set RefundDue=@RefundDue where TaxPayerID=@TaxPayerID;
		select * from TaxReturnStatement;
        /*return (result);*/
end;
    