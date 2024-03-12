import AnalystPriceTarget from "./AnalystPriceTarget";
import AnalystRating from "./AnalystRating";

export default interface Analyst {
    rating: AnalystRating;
    priceTarget: AnalystPriceTarget;
    numberOfAnalysts: number;
}
