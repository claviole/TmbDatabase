



//function to create training indexes within the database when a new employee is added
function create_training() {
    fetch('create_training.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}