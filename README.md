# Points of Sale (POS) System  

A **Point of Sale (POS) Terminal** designed to help businesses track sales, manage inventory, and generate detailed reports.  

## 🛠️ Tech Stack

**Backend**:
- PHP 8.1+ (OOP architecture)
- MySQL Database

**Frontend**:
- Vue.js 2 
- Bootstrap 5

**Infrastructure**:
- Docker
- Apache web server

## Table of Contents
- [Features](#-features)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [License](#-license)

## ✨ Features  

### 🏷️ POS Terminal  
- Add items to checkout  
- Search products by name or browse by categories  
- Auto-print receipts (tested on Epson printers)  

### 📦 Products Management  
- Add, edit, and delete products  
- Generate barcodes for products  

### 🗂️ Categories  
- Add, edit, and delete categories (used for product organization)  

### 📥 Purchases  
- Add, edit, or cancel purchased products (updates inventory automatically)  

### 📊 Sales Reports  
- **Sales Invoice**: Filter by payment type, customer, or date  
- **Sold Ranking**: View best-selling products (date-filterable)  
- **Product Sales**: Search by product/category + date; printable reports  
- **XRead**: Daily employee sales ranking  
- **YRead**: Employee sales ranking across custom date ranges  

### 📦 Inventory Management  
- Search by product name, barcode, category, or date  
- Track:  
  - **Beginning** (yesterday's ending stock)  
  - **+ Purchases** | **- Sold** | **- Spoilage**  
  - **= Current Ending Count**  
- **Low-stock alerts**: Ending count turns red if below predefined threshold  

### 💰 Cash Drop  
- Log cash remittances to reconcile with sales reports  
- Visual indicators:  
  - Green: Overpayment  
  - Red: Shortage  
- Searchable by date  

### 💸 Expenses  
- Track business expenses to calculate net income  

### 🔄 Change Item  
- Process item exchanges:  
  - Returned items → added to inventory  
  - New items → deducted from inventory  

### 🗑️ Spoilage  
- Record damaged/lost items to adjust inventory  

### 🏢 Suppliers  
- Add, edit, or delete suppliers (linked to purchases)  

### ⚙️ Settings (Admin Only)  
- Manage accounts, passwords, employees, customers, and users  
- Printer settings (currently disabled)  

### 🔐 Login/Logout  
- Login with credentials created in Settings  
- Manual logout required (sessions persist until logout)  

## 🛠️ Prerequisites  
- [Docker](https://www.docker.com/)  

## 🗃️ Database Setup
1. **Import the SQL file**
  - SQL file is located at database/php_pos.sql

## 🚀 Installation  
1. **Clone the repository**:  
   ```bash
   git clone https://github.com/kelason/PointsOfSales.git  
 2. **Navigate to the project directory**:
    ```bash
    cd your-project
 3. **Docker Build**
    ```bash
    docker build -t php-pos -f docker/Dockerfile .
 4. **Docker up**
    ```bash
    docker-compose up app db
 5. **Docker down**
    ```bash
    docker-compose down
    ```

## 🌐 Accessing the App

Once the Docker containers are up and running, you can access the application in your browser:

- **URL**: [http://localhost:8081/grocery/](http://localhost:8081/grocery/)
- **Username**: `admin`
- **Password**: `admin`

## 📜 License

This project is **proprietary software** owned solely by [Kevin Sapang]. All rights reserved.

### What this means:
- You may **not**:
  - Copy, modify, or distribute the code without explicit permission
  - Use the code for commercial purposes other than your own

### Permissions (optional):
- [✓] You may clone and run this software for personal evaluation
- [ ] You may not use this in production without purchasing a license
- [ ] You may not redistribute modified versions

For licensing inquiries, contact: [kevin.sapang@gmail.com]
