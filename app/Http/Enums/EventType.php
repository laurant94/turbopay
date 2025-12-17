<?php

namespace App\Http\Enums;

enum EventType: string
{
    case PaymentRequestTransferred = 'payment_request.transferred';
    case PaymentRequestDeclined = 'payment_request.declined';
    case PaymentRequestCanceled = 'payment_request.canceled';
    case PaymentRequestApproved = 'payment_request.approved';
    case PaymentRequestCreated = 'payment_request.created';

    case PayoutStarted = 'payout.started';
    case PayoutSent = 'payout.sent';
    case PayoutFailed = 'payout.failed';
    case PayoutDeleted = 'payout.deleted';
    case PayoutUpdated = 'payout.updated';
    case PayoutCreated = 'payout.created';

    case TransactionTransferred = 'transaction.transferred';
    case TransactionRefunded = 'transaction.refunded';
    case TransactionDeleted = 'transaction.deleted';
    case TransactionCanceled = 'transaction.canceled';
    case TransactionDeclined = 'transaction.declined';
    case TransactionApproved = 'transaction.approved';
    case TransactionCreated = 'transaction.created';

    case CustomerUpdated = 'customer.updated';
    case CustomerDeleted = 'customer.deleted';
    case CustomerCreated = 'customer.created';
}
