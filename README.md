# Vehicle Service Management System (VSMS)

A full-stack web-based Vehicle Service Management System developed as a final-year degree project and real-world case study solution for an automotive service company.

---

## Project Overview

The Vehicle Service Management System (VSMS) is designed to digitize and streamline vehicle service operations within an automotive service company.  

The system eliminates manual record-keeping, improves service tracking, enhances accountability, and provides structured reporting for management decision-making.

This project was developed as:

- A final year academic project  
- A real-world company case study solution  
- A professional portfolio project  

---

## Problem Statement

Many small to medium automotive workshops manage service operations manually using notebooks or spreadsheets. This leads to:

- Poor service tracking
- Lack of accountability
- No centralized vehicle history
- Difficulty generating monthly and yearly reports
- Inefficient service assignment to technicians

VSMS provides a structured digital workflow to solve these operational challenges.

---

## System Objectives

- Implement role-based authentication and access control
- Enable structured service assignment (Admin → Technician)
- Track service status (Pending / Completed)
- Maintain vehicle service history
- Generate monthly and yearly reports
- Support PDF export for reports
- Provide centralized user management

---

## System Actors

### 1️ Admin / Manager
- Create and manage users
- Activate / deactivate user accounts
- Register vehicles
- Create services
- Assign services to technicians
- View system-wide reports
- Generate monthly & yearly reports (PDF)

### 2️ Technician (Staff)
- View assigned services
- Update service status
- Complete service tasks

### 3️ Customer
- View personal vehicles
- View vehicle service history

> Future Enhancement: Customers will be able to book services directly.

---

## Core Features

- Role-Based Authentication
- User Management (Admin Control)
- Vehicle Registration & Management
- Service Creation & Assignment Workflow
- Monthly & Yearly Reporting
- PDF Report Generation (TCPDF)
- Structured Service Records
- Status Tracking (Pending / Completed)

---

## Technology Stack

- **Backend:** PHP (Procedural)
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap
- **Reporting Engine:** TCPDF
- **Version Control:** Git & GitHub
- **Development Environment:** XAMPP

---

## Database Structure

Main Tables:

- `users`
- `vehicles`
- `services`
- `service_records`
- `payments`

The system follows relational integrity using foreign keys to maintain data consistency.

---


Default Demo Credentials
Role	        Email	                    Password

Manager	        talkimkuula@gmail.com       talkimkuula

Technician	    annajohn@gmail.com          annajohn

Technician      juma@gmail.com              juma

Customer	    allyally@gmail.com          allyally

Customer	    hamisabdallah@gmail.com     hamisabdallah



## Installation Guide

### Clone the Repository

```bash
git clone https://github.com/Tfire12/vsms.git
