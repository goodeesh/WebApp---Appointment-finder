<div class="row justify-content-center" id="container-appointment">
    <div class="col"></div>
    <div class="col-6" style="background-color: transparent; display: flex; justify-content: center; align-items: center;">
        <form class="form-group" action="" >
            <label for="title"> Title:</label><br>
            <input class="input-form" id="title" type="text">
            <br>
            <label for="location"> Location:</label><br>
            <input class="input-form" id="location" type="text">
            <br>
            <label for="duration"> Duration:</label><br>
            <input class="input-form" id="duration" type="number">
            <br>
            <label for="description"> Description:</label><br>
            <textarea name="" id="description" cols="30" rows="10"></textarea>
            <br>
            <label for="date"> When:</label><br>
            <input class="input-form" id="date" type="date"><br>
            <input class="input-form70" id="time" type="time">
            <button id="add" type="button" class="btn btn-dark" onclick="addItem()">+</button>
            <br>
            <div id="Appointmentlist">
                
            </div>
            <label for="date">Expiry date:</label><br>
            <input class="input-form" id="expiring_date" type="date">
            <br>
            <br>
            <button type="button" class="btn btn-dark" onclick="hide()">Cancel</button>
            <button type="Submit" class="btn btn-dark"id="submit" onclick="savedata()">Submit</button>
        </form>
    </div>
    <div class="col"></div>
</div>