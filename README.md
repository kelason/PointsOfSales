# Points of Sales (POS)

A POS terminal that can help the businesses to track their sales. It comes with many features that can be explained down below.

## Features
- POS terminal
  - Ability to insert items to be checkout.
  - Search for product or by clickng it by categories
  - Auto print receipt (tested on epson printer)
- Products
  - Add, Edit and Delete products that can be display in the POS terminal
  - Can generate Barcode
- Catergories
  - Add, Edit and Delete Catergories that can be pick in the Products
- Purchases
  -  Add, Edit and Cancel a purchased products that can be added to the inventory
- Sales Report
  - Sales Invoice
    - Shows sales report by payment type, customer, and date
  - Sold Ranking
    - Shows the best selling product and can be change by date
  - Product Sales
    - Show a report list of product sales that can be search by product or categories and by date. It can also print the result.
  - XRead
    - Show the ranking of sales of employees by a single date.
  - YRead
    - Show the ranking of sales of employees by from date and to date.
- Inventory
  - Can search by product name or barcode, categories and by date
  - Add
  - Show the report of the product that added using purchase, subtracted using POS Terminal, subtractedusing spoilage and show the current ending count of the product.
    - Beginning (yesterdays ending) | + Purchase - Sold - Spoilage = Current Ending count
  - Show red in the ending count means the alarm level set in the products are met
    - EX. set an alarm level to Coke by 100 when the count becomes 99 it will show red means it needs to purchase more.
- Cash Drop
  - Can set the cash sales to remit it so we can see in the sales report the actual sales using Cash count
  - Show green if the money is over or Red if the money is short
  - Can search by date
- Expense
  - Can set to see the expense and shows the actual generated income of the business in the Sales Report
- Change Item
  - Can change the customers item
    - The received item added to the inventory and the changed item is subtracted to the inventory 
- Spoilage
  - Can be set here if the item is damaged or accidentally destroyed
- Supplier
  - Can add, Edit and Delete supplier that can be seen the the purchase product
- Settings
  - Ability to change accounts information, change password, Add/Edit/Delete employees, customer, and user.
    - Only admin user have access to this feature
  - Printer settings (disabled for now)
- Login
  - Can login created accounts in the settings
- Logout
  - delete the users session to the POS site
  - This site do not auto deletes user session and can be login until the user desides to use the logout
 
## Prerequisites
1. Docker
   ```bash
   https://www.docker.com/
   
 ## Installation
 1. Clone the repository:
    ```bash
    git clone https://github.com/kelason/PointsOfSales.git
 2. Navigate to the project directory:
    ```bash
    cd your-project
 3. Docker Build
    ```bash
    docker build -t php-pos -f docker/Dockerfile .
 4. Docker up
    ```bash
    docker-compose up app db
 5. Docker down
    ```bash
    docker-compose down
