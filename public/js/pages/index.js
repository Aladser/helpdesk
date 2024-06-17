/**форма фильтра*/
let filter_form = document.querySelector('#task-filter-form');
/**кнопки фильтра задач*/
let filter_switchers = new Map([
    ['all', document.querySelector('#task-filter-form__all')],
    ['new', document.querySelector('#task-filter-form__new')],
    ['process', document.querySelector('#task-filter-form__process')],
    ['completed', document.querySelector('#task-filter-form__completed')]
  ]);

filter_switchers.forEach(switcher => {
    switcher.addEventListener('click', function(e){
        filter_form.submit();
    });
});