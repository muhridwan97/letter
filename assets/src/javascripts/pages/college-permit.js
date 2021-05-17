export default function () {
    const formCollegePermit = $('#form-college-permit');
    const studentTemplate = $('#student-template').html();
    const btnAddStudent = formCollegePermit.find('#btn-add-student');
    let studentWrapper = formCollegePermit.find('#student-wrapper');
    let totalStudent = studentWrapper.children().length;

    btnAddStudent.on('click', function (e) {
        e.preventDefault();
        studentWrapper.append(
            studentTemplate
                .replace(/{{no}}/g, (totalStudent + 1))
                .replace(/{{index}}/g, totalStudent)
        );
        totalStudent++;
    });
    studentWrapper.on('click', '.btn-remove-student', function (e) {
        e.preventDefault();
        var btnSubmit = $(this).closest('form');
        btnSubmit.find(':submit').attr('disabled', false);

        totalStudent--;

        $(this).closest('.card-student').remove();

    });
};
