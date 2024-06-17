/**форма фильтра*/
let filter_form = document.querySelector('#task-filter-form');
/**кнопки фильтра задач*/
let filter_switchers = new Map([
    ['all', document.querySelector('#task-filter-form__all')],
    ['opened', document.querySelector('#task-filter-form__opened')],
    ['process', document.querySelector('#task-filter-form__process')],
    ['closed', document.querySelector('#task-filter-form__closed')]
  ]);

filter_switchers.forEach(switcher => {
    switcher.addEventListener('click', function(e){
        filter_form.submit();
    });
});