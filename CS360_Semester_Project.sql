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

create table GrossTaxableIncomes
	(TaxPayerID				numeric(20, 0) not null,
	Amount					numeric(10, 2) not null,
    FederalTaxesWithheld	numeric(10,2),
	Type					varchar(20),
	primary key(TaxPayerID, Amount));

create table StudentLoans
	(TaxPayerID			numeric(20, 0) not null,
	Amount				numeric(10, 2),
	primary key(TaxPayerID),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID));
	
    
create table OwnsHome
	(TaxPayerID			numeric(20, 0) not null,
	StreetAddress	varchar(30),
    City 			varchar(20),
    State			varchar(10),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, StreetAddress, City));
    
create table OwnsRental
	(TaxPayerID			numeric(20, 0) not null,
	StreetAddress	varchar(30),
    City 			varchar(20),
    State			varchar(10),
    RepairCosts		numeric(10,2),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, StreetAddress, City));
    
create table WorkExpenses
	(TaxPayerID		numeric(20,0) not null,
	Description		varchar(50),
	Amount	numeric(10, 2),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
    primary key(TaxPayerID));
    
create table CityTax
	(City varchar(20) not null,
    State varchar(20) not null,
    Amount numeric(10,2) not null,
    primary key(City, State)
    );
    
create table OwnsBusiness
	(TaxPayerID		numeric(20,0) not null,
	BusinessName	varchar(20) not null,
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, BusinessName));
    
    
create table EmployedBy
	(TaxPayerID		numeric(20,0) not null,
	EmployerID		numeric(20,0) not null,
    EmployerName	varchar(30),
	foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, EmployerID));

create table RentedHome
	(TaxPayerID		numeric(20,0) not null,
	Address 		varchar(30) not null,
    StartDate		numeric(8,0),
    EndDate			numeric(8,0),
    foreign key(TaxPayerID) references TaxPayers(TaxPayerID),
	primary key(TaxPayerID, Address));
    
/* Query to determine total taxes withheld */
set @TotalTaxesWithheld=(select sum(FederalTaxesWithheld)
from GrossTaxableIncomes
where TaxPayerID=@TaxPayerID);

/* Automatic Deductions: automatic=$3000, female/senior=$500, handicap=$750, woundedvet=$2000 */
/* To calculate this, we must sum the automatic deduction with all other possible itemized deductions */
/* See here for other things we want to deduct: https://www.policygenius.com/blog/tax-deductions-tax-credits-you-can-take/ */
set @IsWoundedVet = case /* case woundedvet='yes' then 1 case woundedvet='no' then 0 */
	when (select IsWoundedVet from TaxPayers where TaxPayerID=@PID) = "Yes" then 1
    else 0
end;
set @IsWomanOrElderly = case /* same as above, but with the gender and elderly columns in TaxPayers row */
	when (select Gender from TaxPayers where TaxPayerID=@PID) = "Female" then "1"
    when (select IsElderly from TaxPayers where TaxPayerID=@PID) = "Yes" then "1"
end;
set @IsHandiCap = case
	when (select IsHandicapped from TaxPayers where TaxPayerID=@PID) = "Yes" then 1
	else 0
end;
set @MaxDeduction= 700 + /* Base automatic deduction */
	(@IsWoundedVet*2000) /* Wounded vet deduction */
    +(@IsWomanOrElderly*500) /* Woman or elderly deduction */
    +(@IsHandicap*750); /*Add other deductions for home and itemized expenses */
 
 /* We will store the final tax return info for a given taxpayer in this table */
create table TaxReturnStatement
	(TaxPayerID				numeric(20, 0) not null,
	AmountOwed				numeric(10, 2),
    RefundDue				numeric(10,2),
	primary key(TaxPayerID));
    
set @TaxableIncome=(select sum(Amount) from GrossTaxableIncomes where TaxPayerID=@TaxPayerID);

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
