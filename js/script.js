document.getElementById("shops").addEventListener('click', getShops);
document.getElementById("orders").addEventListener('click', getOrders);
document.getElementById("routes").addEventListener('click', getRoutes);
document.getElementById("spam").addEventListener("keypress", disableFormSubmit);

$(function () {
    $('form').submit(function () {
        var formIsValid = true;
        var nameShop = document.getElementById("nameShop").value;
        var numberProduct = document.getElementById("numberProduct").value;
        var productCount = document.getElementById("productCount").value;
        var dateOrder = document.getElementById("dateOrder").value;
        var arr = [];
        if (nameShop === "") {
            document.getElementById("nameShopErr").innerHTML = "Напишите имя магазина";
            formIsValid = false;
        }
        else {
            document.getElementById("nameShopErr").innerHTML = "";
        }
        if (numberProduct === "") {
            document.getElementById("numberProductErr").innerHTML = "Напишите номер торта";
            formIsValid = false;
        }
        else {
            document.getElementById("numberProductErr").innerHTML = "";
        }

        if (productCount === "") {
            document.getElementById("productCountErr").innerHTML = "Напишите количества";
            formIsValid = false;
        }
        else {
            document.getElementById("productCountErr").innerHTML = "";
        }
        if (dateOrder === "") {
            document.getElementById("dateOrderErr").innerHTML = "Напишите дату";
            formIsValid = false;
        }
        else {
            document.getElementById("dateOrderErr").innerHTML = "";
        }


        if (formIsValid) {
            var str;
            var error = [];
            arr.push(nameShop, numberProduct, productCount, dateOrder);
            str = JSON.stringify(arr);

            $.ajax({
                type: "POST",
                data: {name: str},// Сеарилизуем объект
                dataType: "html", //формат данных
                url: "form.php",
                cache: false,
                success: function (response) { //Данные отправлены успешно
                    error = JSON.parse(search(response));


                    document.getElementById("nameShopErr").innerHTML = error[0];
                    document.getElementById("numberProductErr").innerHTML = error[1];
                    document.getElementById("productCountErr").innerHTML = error[2];
                    document.getElementById("dateOrderErr").innerHTML = error[3];
                },
                error: function (response) { // Данные не отправлены
                    alert('Ошибка. Данные не отправлены.');
                }
            });
        }
        return false;
    });
});

function search(text) {
    var regex = 'true';
    if (text.indexOf(regex) > 0) {
        alert("Отправка прошло успешно");
    }
    else {
        return text;
    }
}

function disableFormSubmit() {
    document.getElementById("submit").disabled = true;
}

function getShops() {
    menu.innerHTML = "";
    var str = 'shops';
    ajax(str);

}

function getOrders() {

    var text;
    var menu = document.getElementById("menu");
    var label = document.createElement("label");
    var input = document.createElement("input");
    menu.innerHTML = "";
    label.setAttribute("for", "input");
    text = document.createTextNode('Вибирите дату');
    label.appendChild(text);
    input.setAttribute("type", 'date');
    input.setAttribute("id", "input");
    label.appendChild(input);
    menu.appendChild(label);
    document.getElementById("input").addEventListener('keyup', function () {
        periodChange('orders');
    });
    var str = 'orders';
    ajax(str);

}


function orderDate() {

    var arr = [];
    var date = document.getElementById("input").value;
    arr.push('orders', date);
    arr = JSON.stringify(arr);
    if (date.length >= 10) {
        ajax(arr);
    }
}

function routeDate() {
    var arr = [];
    var date = document.getElementById("input").value;
    var route = parseInt(document.getElementById("input2").value);
    arr.push('routes', date, route);
    arr = JSON.stringify(arr);
    if (date.length >= 10 || route >= 1 && route <= 10) {
        ajax(arr);
    }
}

function getRoutes() {
    var text, text2;
    var menu = document.getElementById("menu");
    var label = document.createElement("label");
    var input = document.createElement("input");
    var label2 = document.createElement("label");
    var input2 = document.createElement("input");
    menu.innerHTML = "";
    label.setAttribute("for", "input");
    label2.setAttribute("for", "input2");
    label2.setAttribute("id", "label2");
    text = document.createTextNode('Вибирите дату');
    text2 = document.createTextNode('Вибирите маршут');
    label.appendChild(text);
    label2.appendChild(text2);
    input.setAttribute("type", 'date');
    input2.setAttribute("type", 'number');
    input2.setAttribute("min", '1');
    input2.setAttribute("max", '10');
    input.setAttribute("id", "input");
    input2.setAttribute("id", "input2");
    label.appendChild(input);
    label2.appendChild(input2);
    menu.appendChild(label);
    menu.appendChild(label2);
    document.getElementById("input").addEventListener('keyup', function () {
        periodChange('routes');
    });
    document.getElementById("input2").addEventListener('keyup', function () {
        periodChange('routes');
    });
    var str = 'routes';
    ajax(str);
}

function ajax(str) {

    $.ajax({
        type: "POST",
        data: {name: str},// Сеарилизуем объект
        dataType: "html", //формат данных
        url: "ajax.php",
        cache: false,
        success: function (response) {  //Данные отправлены успешно
            var arr = JSON.parse(response);
            var key = [];
            var value = [];
            console.log(arr);
            var table, field, field2, column, node, container, text, nominal;
            container = document.getElementById("container");
            container.innerHTML = "";
            // <p>Закрыть</p>
            var close = document.createElement("p");
            close.setAttribute("id", "close");
            text = document.createTextNode('Закрыть');
            close.appendChild(text);
            container.appendChild(close);
            table = document.createElement("table");
            table.setAttribute("id", "customers");
            for (var i = 0; i < arr.length; i++) {
                key = Object.keys(arr[i]);
                value = Object.values(arr[i]);
                field = document.createElement("tr");
                if (i === 0) {
                    nominal = document.createElement("th");
                    node = document.createTextNode("N%");
                    nominal.appendChild(node);
                    field.appendChild(nominal);
                    for (var j = 1; j <= key.length; j++) {
                        column = document.createElement("th");
                        node = document.createTextNode(key[j - 1]);
                        column.appendChild(node);
                        field.appendChild(column);
                    }
                }

                field2 = document.createElement("tr");
                nominal = document.createElement("td");
                node = document.createTextNode(i + 1);
                nominal.appendChild(node);
                field2.appendChild(nominal);
                for (j = 1; j <= value.length; j++) {
                    column = document.createElement("td");
                    node = document.createTextNode(value[j - 1]);
                    column.appendChild(node);
                    field2.appendChild(column);
                }
                table.appendChild(field);
                table.appendChild(field2);
                container.appendChild(table);
            }
            document.getElementById("close").addEventListener('click', function () {
                container.innerHTML = "";
                menu.innerHTML = "";
            });
        }
    });
}

var periodicChangeTimer;

function periodChange($value) {
    clearTimeout(periodicChangeTimer);
    if ($value === 'orders') {
        periodicChangeTimer = setTimeout(orderDate, 1000);
    }
    else if ($value === 'routes') {
        periodicChangeTimer = setTimeout(routeDate, 1000);
    }
}

function validateForm() {

}



