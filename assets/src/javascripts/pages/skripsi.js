import variables from "../components/variables";

export default function () {
    const formSkripsi = $('#form-skripsi');
    const selectStudent = formSkripsi.find('#student');
    const selectPembimbing = formSkripsi.find('#pembimbing');
    const selectPembimbingId = formSkripsi.find('#id_pembimbing');

    selectStudent.on('change', function (e) {
        e.preventDefault();
        selectPembimbing.val('');
        selectPembimbingId.val('');
        selectPembimbing.attr("placeholder",'Fetching data...');
        fetch(variables.baseUrl + 'master/student/ajax-get-pembimbing?id_student=' + formSkripsi.find('#student').val())
            .then(result => result.json())
            .then(data => {
                if(data.nama_pembimbing != null){
                    selectPembimbing.val(data.nama_pembimbing);
                    selectPembimbingId.val(data.id_pembimbing);
                }else{
                    selectPembimbing.attr("placeholder",'Pembimbing not set');
                }
            })
            .catch(err => {
                console.log(err);
            });
    });
};