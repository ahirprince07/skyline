function get_bookings()
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/bookings.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        document.getElementById('bookings-data').innerHTML = this.responseText;
    }
    xhr.send('get_bookings');
}

function toggle_status(id, val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/bookings.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        if(this.responseText == 1){
            alert('success', 'Status updated successfully!');
            get_bookings();
        }
        else{
            alert('error', 'Status update failed!');
        }
    }
    xhr.send('toggle_status=' + id + '&value=' + val);
}

function toggle_access(id, val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/bookings.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        if(this.responseText == 1){
            alert('success', 'Visit status updated successfully!');
            get_bookings();
        }
        else{
            alert('error', 'Visit status update failed!');
        }
    }
    xhr.send('toggle_access=' + id + '&value=' + val);
}

function cancel_booking(id)
{
    if(confirm("Are you sure, you want to cancel this booking?"))
    {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/bookings.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function(){
            if(this.responseText == 1){
                alert('success', 'Booking cancelled successfully!');
                get_bookings();
            }
            else{
                alert('error', 'Booking cancellation failed!');
            }
        }
        xhr.send('cancel_booking=' + id);
    }
}

function search_booking(search_val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/bookings.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        document.getElementById('bookings-data').innerHTML = this.responseText;
    }
    xhr.send('search_booking&search=' + search_val);
}

window.onload = function(){
    get_bookings();
}
