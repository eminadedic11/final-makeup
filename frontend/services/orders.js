var OrdersService = {
    fetch_orders: function() {
        $.ajax({
            url: '../backend/rest/orders/report' + '?start=' + 0 + '&length=' + 15,
            type: 'GET',
            success: function(data) {
                console.log(data);
                OrdersService.append_orders(data);
            }
        });
    },
    append_orders: function(orders) {
        var tableBody = $('#order-details tbody');
        tableBody.empty();
       
        orders.forEach(function(order) {
            var row = $('<tr></tr>');
            row.append('<td class="text-center">' + order.details + '</td>');
            row.append('<td>' + order.order_number + '</td>');
            row.append('<td>' + order.total_amount + '</td>');
            tableBody.append(row);
        });
    },
};
