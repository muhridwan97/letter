import variables from './variables';

export default function () {

    const SUBSCRIBE_REQUISITION = 'requisition';
    const SUBSCRIBE_OFFER = 'offer';
    const SUBSCRIBE_ORDER = 'order';

    const EVENT_REQUISITION_MUTATION = 'requisition-mutation';
    const EVENT_REQUISITION_VALIDATION = 'requisition-validation';
    const EVENT_REQUISITION_PROCEED = 'requisition-proceed';
    const EVENT_OFFER_MUTATION = 'offer-mutation';
    const EVENT_ORDER_MUTATION = 'order-mutation';

    function displayNotification(title, message) {
        let options = {
            body: message,
            icon: variables.baseUrl + 'assets/dist/img/layouts/icon.png',
        };
        new Notification(title, options);
    }

    if(variables.userId) {
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

                let channelRequisition = pusher.subscribe(`${SUBSCRIBE_REQUISITION}-${variables.userId}`);
                channelRequisition.bind(EVENT_REQUISITION_MUTATION, function (data) {
                    displayNotification('Requisition', data.message);
                });
                channelRequisition.bind(EVENT_REQUISITION_VALIDATION, function (data) {
                    displayNotification('Requisition Validation', data.message);
                });
                channelRequisition.bind(EVENT_REQUISITION_PROCEED, function (data) {
                    displayNotification('Requisition Proceed', data.message);
                });

                let channelOffer = pusher.subscribe(`${SUBSCRIBE_OFFER}-${variables.userId}`);
                channelOffer.bind(EVENT_OFFER_MUTATION, function (data) {
                    displayNotification('Purchase Offer', data.message);
                });

                let channelOrder = pusher.subscribe(`${SUBSCRIBE_ORDER}-${variables.userId}`);
                channelOrder.bind(EVENT_ORDER_MUTATION, function (data) {
                    displayNotification('Purchase Order', data.message);
                });
            }
        } else {
            console.log('Not support notification');
        }
    }

};
