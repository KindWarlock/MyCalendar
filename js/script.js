let dates = document.querySelectorAll('.input-date');
for (let i = 0; i < dates.length; i++) {
    dates[i].valueAsDate = new Date();
}

function show_card(id) {
    let curr_task;

    for (let i = 0; i < tasks_data.length; i++) {
        if (tasks_data[i]['id'] == id) {

            curr_task = tasks_data[i];
        }
    }
    
    document.getElementById('name').value = curr_task['name'];
    document.getElementById('type').value = curr_task['type'];
    document.getElementById('place').value = curr_task['place'];
    document.getElementById('date').valueAsDate = new Date(curr_task['dt']);
    document.querySelector('.task__input-time').value = curr_task['dt'].substr(11, 8);
    
    // duration
    curr_task['duration'] = Number(curr_task['duration']); 
    if (curr_task['duration'] % 60 === 0) {
        curr_task['duration'] /= 60;
        document.querySelector('.duration__select').value = 2;    
    }
    document.getElementById('duration').value = curr_task['duration'];

    document.getElementById('comment').innerHTML = curr_task['comment'];
    document.querySelector('.task__submit').value = 'Обновить';
    
    //invisible id field
    let elem = document.createElement("input");
    elem.type = "text";
    elem.name = "id";
    elem.value = curr_task['id'];
    elem.classList.add('id_field');
    document.querySelector('.task__form').append(elem);
}

