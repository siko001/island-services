### **Installation Steps**

-   Clone the repo
-   Install composer dependancies

    ```
    composer install
    ```

-   Install node dependancies

    ```
    npm install
    ```

-   Compile the assests

    ```
    npm run build
    ```

-   Copy and Configure the Environment File

    ```
    cp .env.example .env
    ```

-   Generate Application Key

    ```
    php artisan key:generate
    ```

-   Set Up Database & run migration For the Central Database

    ```
    php artisan migrate
    ```

-   Follow Tenancy Information to create tenants

---

#### Tenancy Creation

For ease of tenancy creation a developer can run an Artisan command and this will create a tenant based on the information entered in the console.

Run the below

```
php artisan custom-tenant:create
```

This will prompt you to enter

-   Tenant Name - this will also create the domain name removing whitespace and capital letters.
-   Super Admin Email - This will be used to login
-   Super Admin Password - This will be used to login
-   Logo Path - Logo need to be manually uploaded to server, this is to have a logo for the tenant (optional)

Once the Command has been executed, the Permission and Role seeder will kick in. This will create all the permissions based on all the resources available , it will also create roles, and assign the "Super Admin" role to user entered.

---

# Database Seeder

Open tenants/DatabaseSeeder.php and fill up the information as necessary

**Example**

'name' => 'Neil',

Once filled run the below in the terminal

```
php artisan tenants:seed
```

This will create a user with the details you entered **(this will be the super admin)**.
**This will also call all necessary seeders to fill the database with information to get the application up and running for all tenancies available.**

---

More in depth information can be found here:
https://team.9hdigital.com/spaces/island-beverages/page/8883-technical-documentation
