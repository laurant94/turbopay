export const actionButtonClass = "px-2 py-2 inline-flex items-center rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring transition";
export const primaryVariantClass = "bg-blue-600 hover:bg-blue-700 border-transparent text-white";
export const secondaryVariantClass = "bg-gray-200 hover:bg-gray-300 border-gray-300 text-gray-800";
export const dangerVariantClass = "bg-red-600 hover:bg-red-700 border-transparent text-white";
export const successVariantClass = "bg-green-600 hover:bg-green-700 border-transparent text-white";
export const warningVriantClass = "bg-yellow-500 hover:bg-yellow-600 border-transparent text-white";

export const pendingStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";
export const successfulStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";
export const failedStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";
export const refundedStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";
export const partially_refundedStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";
export const manual_reviewStatusClass = "bg-primary-500 px-2 py-1 rounded text-white";



export const statusClass = (status)=>{
    switch(status){
        case 'pending':
            return pendingStatusClass;
        case 'successful':
            return successfulStatusClass;
        case 'failed':
            return failedStatusClass;
        case 'refunded':
            return refundedStatusClass;
        case 'partially_refunded':
            return partially_refundedStatusClass;
        case 'manual_review':
            return manual_reviewStatusClass;
        default:
            return pendingStatusClass;
    }
}