var ids_list = []; // Create an empty array to store appointment ids
var username = "";
$(function () {
  loaddata();
});
//function to load the list of appointments
function loaddata() {
  $.ajax({
    type: "GET",
    url: "../backend/serviceHandler.php",
    data: { method: "queryByUser" },
    dataType: "json",
    success: function (response) {
      console.log(response);
      var html = "";
      $("#listOfAppointments").empty();
      ids_list.length = 0; // Clear the array
      for (var index = 0; index < response.length; index++) {
        // create a new row and append it to the table
        var row = $("<tr/>", {
          class: "appointment-element",
          id: "TR" + index,
        });
        $("#listOfAppointments").append(row);

        // add the data to the row
        row.append($("<th/>", { scope: "row" }).append(index + 1));
        row.append($("<td/>").append(response[index].title));
        row.append($("<td/>").append(response[index].location));
        row.append($("<td/>").append(response[index].duration));
        row.append($("<td/>").append(response[index].expiring_date));

        // add a new column indicating whether the appointment is active or inactive
        var today = new Date();
        var expiryDate = new Date(response[index].expiring_date);
        var isActive = expiryDate >= today;
        var status = isActive ? "Active" : "Inactive";
        var statusCell = $("<td/>").append(status);
        if (isActive) {
          row.addClass("active-element");
        } else {
          row.addClass("inactive-element");
        }
        row.append(statusCell);

        // add a new column for delete button
        var deleteCell = $("<td/>");
        var deleteButton = $("<button/>", {
          class: "btn btn-danger btn-sm delete-button",
          text: "Delete",
          "data-appointment-id": response[index].appointment_id, // add data attribute
        });
        deleteCell.append(deleteButton);
        row.append(deleteCell);

        // Save the appointment id to the ids_list array
        ids_list.push(response[index].appointment_id);

        // create a new div and append it after the row
        var div = $("<div/>", { class: "extra-info", id: "DIV" + index });
        $("#listOfAppointments").append(div);
      }
      // Add click event handler for delete button
      $(".delete-button").click(function () {
        var appointmentId = $(this).data("appointment-id");
        var row = $(this).closest("tr");
        //better confirm before deleting
        if (confirm("Are you sure you want to delete this appointment?")) {
          $.ajax({
            type: "GET",
            url: "../backend/serviceHandler.php",
            data: {
              method: "deleteAppointment",
              param: JSON.stringify(appointmentId),
            },
            dataType: "json",
            success: function (response) {
              console.log(response);
              loaddata();  // reload the data
            },
            error: function (xhr, status, error) {
              console.log(xhr.responseText);
            },
          });
        }
      });
    },
  });
}
//specific case when the appointment is inactive, no checkbox and no submit button
$(document).on("click", ".appointment-element.inactive-element", function () {
  if (
    $(event.target).hasClass("delete-button") ||
    $(event.target).attr("id") == "delete-button"
  ) {
    // Do nothing if the click event target is a delete button
    return;
  }
  //get id of the clicked row
  var id = $(this).attr("id");
  //get the index of the row
  var index = id.substring(2);
  console.log(ids_list[index]);
  loadSpecificData(ids_list[index]);
  var div_specific = $(this).next(".extra-info");
  div_specific.slideToggle();
  function loadSpecificData() {
    var selectedIds = {}; // object to store selected timeslot IDs
    $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      data: { method: "queryById", param: ids_list[index] },
      dataType: "json",
      success: function (response) {
        console.log(response);
        $(".timeslot-element").remove();
        // Loop through each timeslot and create a new row for it
        for (var i = 0; i < response[0].timeslots.length; i++) {
          var timeslot = response[0].timeslots[i];
          var timeslot_id = response[0].timeslots[i].timeslot_id;
          var timeslotRow = $("<tr/>", {
            class: "timeslot-element centered-row",
            id: "TS" + timeslot_id, // add ID to timeslot element
          });
          var timeslotCell = $("<td/>").attr("colspan", 4);
          timeslotCell.text(
            timeslot.date_time + " (" + timeslot.vote_count + " votes)"
          );
          timeslotRow.append(timeslotCell);
          $("#TR" + index).after(timeslotRow);
          // Add the timeslot to the selectedIds object with the value set to false initially
          selectedIds[timeslot_id] = false;

          // Add hover function to timeslot element
          (function (id) {
            timeslotRow.hover(
              function () {
                show_users(id); // call show_users function with timeslot ID
              },
              function () {
                hide_users(); // call hide_users function to hide user information
              }
            );
          })(timeslot_id);
        }
      },
    });
  }
});
//case when the appointment is active, checkbox and submit button
$(document).on("click", ".appointment-element.active-element", function () {
  if (
    $(event.target).hasClass("delete-button") ||
    $(event.target).attr("id") == "delete-button"
  ) {
    // Do nothing if the click event target is a delete button
    return;
  }
  //get id of the clicked row
  var id = $(this).attr("id");
  //get the index of the row
  var index = id.substring(2);
  console.log(ids_list[index]);
  loadSpecificData(ids_list[index]);
  var div_specific = $(this).next(".extra-info");
  div_specific.slideToggle();

  function loadSpecificData() {
    var selectedIds = {}; // object to store selected timeslot IDs
    $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      data: { method: "queryById", param: ids_list[index] },
      dataType: "json",
      success: function (response) {
        console.log(response);
        $(".timeslot-element").remove();
        // Loop through each timeslot and create a new row for it
        for (var i = 0; i < response[0].timeslots.length; i++) {
          var timeslot = response[0].timeslots[i];
          var timeslot_id = response[0].timeslots[i].timeslot_id;
          var timeslotRow = $("<tr/>", {
            class: "timeslot-element centered-row",
            id: "TS" + timeslot_id, // add ID to timeslot element
          });
          var timeslotCell = $("<td/>").attr("colspan", 4);
          timeslotCell.text(
            timeslot.date_time + " (" + timeslot.vote_count + " votes)"
          );
          var checkboxCell = $("<td/>");
          var checkbox = $("<input>", {
            type: "checkbox",
            name: "timeslot",
            value: timeslot_id,
          });
          checkboxCell.append(checkbox);
          timeslotRow.append(timeslotCell);
          timeslotRow.append(checkboxCell);
          $("#TR" + index).after(timeslotRow);
          // Add the timeslot to the selectedIds object with the value set to false initially
          selectedIds[timeslot_id] = false;
          // Add hover function to timeslot element
          (function (id) {
            timeslotRow.hover(
              function () {
                show_users(id); // call show_users function with timeslot ID
              },
              function () {
                hide_users(); // call hide_users function to hide user information
              }
            );
          })(timeslot_id); // pass in timeslot_id as parameter to hover function
        }

        // Add a submit button
        var submitRow = $("<tr/>", { class: "centered-row timeslot-element" });
        var submitCell = $("<td/>").attr("colspan", 5);
        var submitButton = $(
          '<button class="btn btn-dark timeslot-element">'
        ).text("Submit");
        submitButton.on("click", function () {
          // loop through each checked checkbox and update the corresponding value in selectedIds object
          $("input[name='timeslot']:checked").each(function () {
            var timeslot_id = $(this).val();
            selectedIds[timeslot_id] = true;
          });
          console.log(selectedIds);
          vote(selectedIds); // call vote function with selectedIds object
        });
        submitCell.append(submitButton);
        submitRow.append(submitCell);
        var lastTimeslotRow = $(".timeslot-element").last();
        lastTimeslotRow.after(submitRow);
      },
    });
  }
});
//show users that voted for the timeslot
function show_users(id) {
  function loadusers() {
    $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      data: { method: "queryUsersVoteByTimeslotId", param: id },
      dataType: "json", // set the dataType to "json"
      success: function (response) {
        console.log(response);
        // Extract the JSON string from the response
        var startIndex = response.indexOf("[");
        var endIndex = response.lastIndexOf("]") + 1;
        var jsonString = response.slice(startIndex, endIndex);
        // Parse the JSON string response
        var usernames = JSON.parse(jsonString);
        // find the timeslot element by ID
        var timeslotElement = $("#TS" + id);

        // check if usernames is an array before using the join method
        if (Array.isArray(usernames)) {
          // create a new element for the white box
          var whiteBox = $("<div/>", {
            class: "white-box",
            text: usernames.join(", "), //add names separated by comma
          });
          // append the white box to the timeslot element
          timeslotElement.append(whiteBox);
        } else {
          console.log("Error: response is not an array");
        }
      },

      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }
  loadusers();
}
//hide users that voted for the timeslot
function hide_users() {
  $(".white-box").remove();
}
//function to save the preferences of a user for a specific appointment
function vote(selectedIds) {
  // send selectedIds array to backend via AJAX or perform other actions
  function save_vote() {
    //we send this object to the backend
    var data = {
      username: username,
      timeslots: selectedIds,
    };
    $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      data: { method: "saveVote", param: JSON.stringify(data) },
      dataType: "json",
      success: function (response) {
        console.log(response);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      },
    });
  }
  save_vote();
  loaddata(); //reload the data
}
//function to save the appointment data
function savedata() {
  //prevent to submit button to reload page
  event.preventDefault();
  //save information in variables
  var title = document.getElementById("title").value;
  var location = document.getElementById("location").value;
  var duration = document.getElementById("duration").value;
  var description = document.getElementById("description").value;
  var expiring_date = document.getElementById("expiring_date").value;
  var dates = [];
  var dateElements = document.getElementsByClassName("dates");
  for (var i = 0; i < dateElements.length; i++) {
    dates.push(dateElements[i].textContent.trim());
  }
  //we send this object to the backend
  var data = {
    title: title,
    location: location,
    duration: duration,
    description: description,
    date_time: dates,
    expiring_date: expiring_date,
  };
  $.ajax({
    type: "GET",
    url: "../backend/serviceHandler.php",
    data: { method: "saveData", param: JSON.stringify(data) },
    dataType: "json",
    success: function (response) {
      console.log(response);
    },
    error: function (xhr, status, error) {
      console.log(xhr.responseText);
    },
  });
  loaddata();
}
//small function to show the appointment form
function newAppointment() {
  $("#container-appointment").css("display", "flex");
}
//function to get the name of the user at the beggining
function enterName() {
  username = prompt("Enter your name");
  document.getElementById("username").innerHTML = "Name: " + username;
  $("#container-appointment").fadeOut(0);
}
//hide the appointment form
function hide() {
  $("#container-appointment").fadeOut(0);
}
//logout just reload the page
function logout() {
  location.reload();
}
//add the timeslot possibilities to the form
function addItem() {
  var date = $("#date").val();
  var time = $("#time").val();
  var repeated = false;
  var count = $("#Appointmentlist li").length + 1;
  if (date.trim() === "" || time.trim() === "") {
    // Display an error message or do nothing
    return;
  }
  var appointment = date + " , " + time;
  var existing_appointments = $("#Appointmentlist li").filter(function () {
    return $(this).text() === appointment;
  });
  if (existing_appointments.length > 0) {
    repeated = true;
  }
  if (!repeated) {
    console.log(date);
    console.log(time);
    $("#Appointmentlist").append(
      '<br><li class="dates" id="' +
        "termine" +
        count +
        '">' +
        date +
        " , " +
        time +
        "</li>"
    );
    console.log("element added");
    $("#date").val("");
    $("#time").val("");
  }
}
