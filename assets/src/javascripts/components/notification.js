import variables from './variables';

export default function () {

	const SUBSCRIBE_SYLLABUS = 'syllabus';
	const SUBSCRIBE_TRAINING = 'training';

	const EVENT_CURRICULUM_MUTATION = 'curriculum-mutation';
	const EVENT_COURSE_MUTATION = 'course-mutation';
	const EVENT_LESSON_MUTATION = 'lesson-mutation';
	const EVENT_TRAINING_ASSIGNED = 'training-assigned';
	const EVENT_EXAM_ASSIGNED = 'exam-assigned';
	const EVENT_EXAM_FINISHED = 'exam-finished';

    function displayNotification(title, message, url = variables.baseUrl) {
        let options = {
            body: message,
            icon: variables.baseUrl + 'assets/dist/img/icon.png',
        };
        const notification = new Notification(title, options);
        notification.onclick = function () {
            window.open(url);
        };
    }

    if (variables.userId) {
        if ('Notification' in window) {
            if (Notification.permission !== "granted") {
                Notification.requestPermission(function (result) {
                    console.log('User choice', result);
                    if (result !== 'granted') {
                        console.log('No notification permission granted');
                    } else {
                        displayNotification('Successfully subscribed!', 'You successfully subscribe to our notification service!');
                    }
                });
            } else {
                //Pusher.logToConsole = true;

                let pusher = new Pusher('26e6e8709320db34adbb', {
                    cluster: 'ap1',
                    encrypted: true
                });

                let channelSyllabus = pusher.subscribe(`${SUBSCRIBE_SYLLABUS}-${variables.userId}`);
				channelSyllabus.bind(EVENT_CURRICULUM_MUTATION, function (data) {
                    displayNotification('Curriculum', data.message, data.url);
                });
				channelSyllabus.bind(EVENT_COURSE_MUTATION, function (data) {
                    displayNotification('Course', data.message, data.url);
                });
				channelSyllabus.bind(EVENT_LESSON_MUTATION, function (data) {
                    displayNotification('Lesson', data.message, data.url);
                });

                let channelTraining = pusher.subscribe(`${SUBSCRIBE_TRAINING}-${variables.userId}`);
				channelTraining.bind(EVENT_TRAINING_ASSIGNED, function (data) {
                    displayNotification('Training', data.message, data.url);
                });
				channelTraining.bind(EVENT_EXAM_ASSIGNED, function (data) {
                    displayNotification('Exam', data.message, data.url);
                });
				channelTraining.bind(EVENT_EXAM_FINISHED, function (data) {
                    displayNotification('Exam Finished', data.message, data.url);
                });
            }
        } else {
            console.log('Not support notification');
        }
    }

};
