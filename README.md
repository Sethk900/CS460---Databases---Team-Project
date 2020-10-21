# CS360_Project

# Current To-Do List
Complete TaxesOwed query (Trinity)
Complete MaxDeduction query (Seth)
Implement a simple web interface to run queries on the database
Write insert statements to add sample data to the database

# Prospective Timeline:
NLT 07 September:  
  -Generate sample data (credit card expense dataset, sample W-2, and any other forms we want to use)  
  -Draft E-R Diagram  
  -Draft a design of the application based on the assignment specifications  
  -Begin drafting a presentation of the prepared documents (application workflow design, E-R diagram, and sample data)  
NLT 18 Septemmber:  
  -Decide on specific tools for hosting the database and web interface  
  -Decide on a host language for running scripts along with SQL (probably Python or C?)  
  -Begin drafting backend SQL queries and scripts necessary to draw from the sample data and generate a .pdf tax return output  
 NLT 28 September:  
  -Finalize E-R diagram and any other documentation used for project planning (application workflow design, sample data, etc.)  
  -Prepare and finalize presentation of the documentation listed above  
 NLT 05 October:  
  -Finalize sample data, stored in a normalized database  
  -Start grinding on the web interface (this will probably be the most difficult part)  
NLT 18 October:  
  -Complete a functional draft of the web interface   
NLT 05 November:  
  -Work out any bugs or issues with the web interface, of which there will likely be many  
  -Make sure all script and query drafts are solid  
NLT 16 November:  
  -Mop up any outstanding issues  
  -Prepare and finalize a presentation of the finished product  
17 November:  
  -Deliver presentation of the finished product  
  
# Assignment Description:
The main focus of the implementation project is to learn how to use a mainstream RDBMS such as Oracle or MySQL. In this course we emphasize only on design of a database, querying using SQL and developing interfaces to a database application. Such interfaces may be in form of a web interface, natural language interface or graphical interface. Students will work in groups of two (at most three), and all students will implement one application, from a list of 2-3 projects listed below. Interim reports and demo on September 29 and November 17/18 must accompany implementation progress/evidence in the form of actual coding and demo. You will upload your project on a web server and make it functional. You will also submit all source code on a public repository such as GitHub or SourceForge for posterity and onward use by other students. Your project will be graded for functionality and its working status, and not just for coding. The user interface will be examined for usability and user experience.

MyTaxes Income Tax Management System Portal

In all societies, legitimate incomes of individuals are taxed for social benefits and to maintain civic services, system and public facilities. Traditionally, the information needed to determine the tax levels of individuals are distributed in various systems. It is thus necessary to assemble and cross reference information to validate income information and claims of expenses to determine taxable income, making the resulting system a cat and mouse type “catch me if you can” exercise.

Instead, in this project, you will design a database for tax management and payment by individual tax payers where the income and expenses and their reporting is centralized around the tax payer. In the MyTaxes system, each person associates him/herself with an income source (job, business, consulting, etc.) and authorizes those sources to report incomes and losses to the person periodically, and summarily at the calendar year end in the form of a printable PDF document such as W-2.

A person also maintains a database of all his expenses in various categories such as tuition, grocery, house rent, mortgage interest, travel expenses (personal/business/job related), medical expenses, and so on. Some business enterprises such as hospitals, insurance companies and banks also send various financial transaction summaries to a person each year. Some of those summaries are used to lower the taxable income of individuals in the form of deductions, e.g., student loan or home mortgage interest payments are tax deductible. 

Design a web-based interface for a tax payer to register, manage and prepare his/her tax returns based on existing tax laws fully automatically in one click, assuming all his/her needed information are already in place, and the system is able to determine a definitive tax burden. As an example, consider the following rules.
1.	Assume that all tax payers/individuals and organizations have a 20-digit identifier of the form XXXX-XXXX-XXXX-XXXX-XXXX.
2.	Gross taxable incomes are wages/salaries, investment interests, rental income, farm/agricultural income, business income, capital gains, and other incomes. Wages/salaries are reported by employers using a form called W-2, investment interests are reported by banks or investment brokers using a form called I-2, rental incomes are reported by the renters using a form called R-2, farm incomes are self-reported by the tax payer using a form called F-2, business income is reported by a business using a form called B-2, capital gains by a bank using C-2, and other incomes are reported by other agencies using form O-2. You are free to design these annual year-end forms as you please and deem reasonable.
3.	Some incomes are deductible from an individual’s total income. They are as follows: all taxpayers receive a personal/basic deduction in the amount of $3,000. A female or senior citizen (age 65 or over) tax payer receives a $500 deduction. A handicap taxpayer receives a $750 deduction. And a wounded veteran of armed forces receives a $2,000 deduction. But no one can receive more than one deduction beyond basic deduction – i.e., use the maximum deduction applicable. The income after these deductions are called taxable income.
4.	All student loans including interests are deductible from taxable income. So are home mortgage interests owned by the tax payer, rental home repair costs owned by the tax payer, and any job-related expenses such as travel expenses. The resulting income after these deductions are called adjusted taxable income.
5.	Adjusted taxable income is taxed using a graduated scale: $1-$5,000 at 10%, next $5,001-$11,000 at 15%, next $11,001-$$18,000 at 20%, next $18,001-$35,000 at 25%, and above $35,000 at 30%.
6.	Certain city dwellers must pay a minimum annual city tax in the amount listed in the city database to compensate for the city services in the event a tax payer has no tax liability (zero taxes) as outlined in step 5 above.
7.	All tax payer must report their employers using the universal 20-digit ID, any business or farm they own, and all the homes they own. A tax paper must also report if they rented a home for personal use using home address along with the rent they have paid.

Prepare the tax return using an appropriate form, showing all personal and tax details, and attaching all the reported X-2 forms. Cross reference all incomes and deductions, and all reporting (e.g., rents paid and earned) for accuracy. Flag all discrepancies for inspection/investigations by the IRS. Save the return for posterity. Show tax liability of the taxpayer as the final entry in your prepared tax return. If a refund is due (because reported taxes withheld by an employer or an agency is higher), show the refund due amount as well. Allow for a taxpayer signature at the bottom along with the submission date.

Once saved and submitted, it cannot be modified. But can be amended and resubmitted any number of times. However, all submissions are permanent and cannot be overwritten – i.e., all submitted versions are maintained.

# Relevant Links:
Using PHP to generate a .pdf from a MySQL database:
https://www.plus2net.com/php_tutorial/pdf-data-student.php
https://www.phpflow.com/php/generate-pdf-file-mysql-database-using-php/


