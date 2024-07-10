<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */
          $servername = 'db1.ibu.edu.ba';
          $dbUsername = 'webmakeup_24';
          $dbPassword = 'web24makePWD';
          $database = 'webmakeup';
          $port = '3306';

          /** TODO
           * Create new connection
           */
          $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $dbUsername, $dbPassword);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get employees performance report
     */
    public function employees_performance_report(){
      $sql = "SELECT employees.employeeNumber, 
      employees.firstName, 
      employees.lastName, 
      SUM(payments.amount) as total
      FROM employees
      JOIN customers ON employees.employeeNumber = customers.salesRepEmployeeNumber
      JOIN payments ON customers.customerNumber = payments.customerNumber
      GROUP BY employees.employeeNumber, employees.firstName, employees.lastName";
      
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);


    }

    /** TODO
     * Implement DAO method used to delete employee by id
     */
    public function delete_employee($employee_id) {
      $sql = "DELETE FROM employees WHERE employeeNumber = :employee_id";

      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':employee_id', $employee_id);
      
      return $stmt->execute();
    }


    /** TODO
     * Implement DAO method used to edit employee data
     */
    public function edit_employee($employee_id, $data){
      $sql = "UPDATE employees SET firstName = :first_name, lastName = :last_name, email = :email 
            WHERE employeeNumber = :employee_id";

      $stmt = $this->conn->prepare($sql);

      $stmt->bindParam(':first_name', $data['first_name']);
      $stmt->bindParam(':last_name', $data['last_name']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':employee_id', $employee_id);

      if ($stmt->execute()) {
        return $this->fetch_employee($employee_id);
      }

      return false;
    }

    private function fetch_employee($employee_id) {
      $sql = "SELECT employeeNumber, firstName, lastName, email FROM employees WHERE employeeNumber = :employee_id";

      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':employee_id', $employee_id);
      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /** TODO
     * Implement DAO method used to get orders report
     */
    public function get_orders_report($offset, $limit) {
      $query = "SELECT o.orderNumber AS order_number,
                       SUM(od.quantityOrdered * od.priceEach) AS total_amount
                FROM orders o
                JOIN orderdetails od ON o.orderNumber = od.orderNumber
                GROUP BY o.orderNumber
                LIMIT {$offset}, {$limit}";
     
      $stmt = $this->conn->prepare($query);
      $stmt->execute();


      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


    /** TODO
     * Implement DAO method used to get all products in a single order
     */
    public function get_order_details($order_id){
      $sql = "SELECT products.productName, 
      orderdetails.quantityOrdered, 
      orderdetails.priceEach 
      FROM orderdetails
      JOIN products ON orderdetails.productCode = products.productCode
      WHERE orderdetails.orderNumber = :order_id";
      
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
      $stmt->execute();
      
      return $stmt->fetchAll(PDO::FETCH_ASSOC);


    }
}
?>
