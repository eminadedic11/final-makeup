<?php

Flight::route('GET /connection-check', function(){
    /** TODO
    * This endpoint prints the message from constructor within ExamDao class
    * Goal is to check whether connection is successfully established or not
    * This endpoint does not have to return output in JSON format
    */
    $examDao= new ExamDao();
});

Flight::route('GET /employees/performance', function(){
     /** TODO
    * This endpoint returns performance report for every employee.
    * It should return array of all employees where every element
    * in array should have following properties
    *   `id` -> employeeNumber of the employee
    *   `full_name` -> concatenated firstName and lastName of the employee
    *   `total` -> total amount of money earned for every employee.
    *              aggregated amount from payments table for every employee
    * This endpoint should return output in JSON format
    * 10 points
    */
    $results = Flight::examService()->employees_performance_report();

    $data = array_map(function($item) {
        return [
            'id' => $item['employeeNumber'],
            'full_name' => $item['firstName'] . ' ' . $item['lastName'],
            'total' => $item['total']
        ];
    }, $results);

    Flight::json($data);

});

Flight::route('DELETE /employee/delete/@employee_id', function($employee_id){
    /** TODO
    * This endpoint should delete the employee from database with provided id.
    * This endpoint should return output in JSON format that contains only 
    * `message` property that indicates that process went successfully.
    * 5 points
    */
    $result =  Flight::examService()->delete_employee($employee_id);

    if ($result) {
        Flight::json(['message' => 'Employee deleted successfully']);
    } else {
        Flight::json(['message' => 'Failed to delete employee'], 400);
    }
});

    

Flight::route('PUT /employee/edit/@employee_id', function($employee_id) {
    /** TODO
    * This endpoint should save edited employee to the database.
    * The data that will come from the form (if you don't change
    * the template form) has following properties
    *   `first_name` -> first name of the employee
    *   `last_name` -> last name of the employee
    *   `email` -> email of the employee
    * This endpoint should return the edited customer in JSON format
    * 10 points
    */
    $data = Flight::request()->data->getData();

    $result = Flight::examService()->edit_employee($employee_id, $data);

    if ($result) {
        Flight::json($result);
    } else {
        Flight::json(['message' => 'Failed to edit employee'], 400);
    }

});

Flight::route('GET /orders/report', function() {
    /** TODO
    * This endpoint should return the report for every order in the database.
    * For every order we need the amount of money spent for the order. In order
    * to get total money for every order quantityOrdered should be multiplied 
    * with priceEach from the orderdetails table. The data should be summarized
    * in order to get accurate report. paginated. Every item returned should 
    * have following properties:
    *   `details` -> the html code needed on the frontend. Refer to `orders.html` page
    *   `order_number` -> orderNumber of the order
    *   `total_amount` -> aggregated amount of money spent per order
    * This endpoint should return output in JSON format
    * 10 points
    */

    $body = Flight::request()->query;
   
    $params = [
        "start" => (int)$body['start'],
        "limit" => (int)$body['length']
    ];


    $orders = Flight::examService()->get_orders_report(
        $params['start'],
        $params['limit']
    );


    if (!empty($orders)) {
        Flight::json($orders);
    } else {
        Flight::json(['message' => 'No orders found'], 404);
    }
});
 
Flight::route('GET /order/details/@order_id', function($order_id){
    /** TODO
    * This endpoint should return the array of all products in a single 
    * order with the provided id. Every food returned should have 
    * following properties:
    *   `product_name` -> productName from the database
    *   `quantity` -> quantity from the orderdetails table
    *   `price_each` -> priceEach from the orderdetails table
    * This endpoint should return output in JSON format
    * 10 points
    */
    $results = Flight::examService()->get_order_details($order_id);

    $formatted_results = array_map(function($item) {
        return [
            'product_name' => $item['productName'],
            'quantity' => $item['quantityOrdered'],
            'price_each' => $item['priceEach']
        ];
    }, $results);

    if (!empty($formatted_results)) {
        Flight::json($formatted_results);
    } else {
        Flight::json(['message' => 'No details found for this order'], 404);
    }

});

?>
