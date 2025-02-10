# Events Booking System

A Laravel event booking system that allows users to browse events, make reservations, and manage their bookings. Includes admin features for event management and user administration.

## Features

### User Features
- Browse available events 
- Search events by name and description
- Make event reservations
- View and manage personal reservations
- Email verification system
- Profile management

### Admin Features
- Event management (CRUD operations)
- View all reservations
- User management 
- Dashboard with statistics
- System monitoring

### Event Management
- Event creation with image upload
- Seat availability tracking
- Date and location management
- Automatic email notifications

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM (for asset compilation)

## Installation

1. Clone the repository:
```bash
git clone git@github.com:davidrivasrodriguez/eventBooking.git
cd eventBooking
composer install
npm install
cp .env.example .env