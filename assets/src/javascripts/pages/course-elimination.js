export default function () {
    const formCourseElimination = $('#form-course-elimination');
    const courseTemplate = $('#course-template').html();
    const btnAddCourse = formCourseElimination.find('#btn-add-course');
    let courseWrapper = formCourseElimination.find('#course-wrapper');
    let totalCourse = courseWrapper.children().length;

    btnAddCourse.on('click', function (e) {
        e.preventDefault();
        courseWrapper.append(
            courseTemplate
                .replace(/{{no}}/g, (totalCourse + 1))
                .replace(/{{index}}/g, totalCourse)
        );
        totalCourse++;
    });
    courseWrapper.on('click', '.btn-remove-course', function (e) {
        e.preventDefault();
        var btnSubmit = $(this).closest('form');
        btnSubmit.find(':submit').attr('disabled', false);

        totalCourse--;

        $(this).closest('.card-course').remove();

    });
};
