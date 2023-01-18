const events = [
    'dashboard:team-id',
    'dashboard:shops-updated',
    'dashboard:products-updated'
]

const emitShopsUpdated = () => {
    const event = new CustomEvent("dashboard:shops-updated");
    document.dispatchEvent(event);
}

const emitProductsUpdated = () => {
    const event = new CustomEvent("dashboard:products-updated");
    document.dispatchEvent(event);
}

const emitTeamIdUpdated = (teamId) => {
    const event = new CustomEvent("dashboard:team-id", { detail: teamId });
    document.dispatchEvent(event);
};

const emitCartUpdated = () => {
    const event = new CustomEvent("dashboard:cart-updated");
    document.dispatchEvent(event);
}
const emitWishlistUpdated = () => {
    const event = new CustomEvent("dashboard:wishlist-updated");
    document.dispatchEvent(event);
}

const emitUpdateWishlist = (wishlist) => {
    const event = new CustomEvent("dashboard:update-wishlist", {detail: wishlist});
    document.dispatchEvent(event);
}

export {
    events,
    emitShopsUpdated,
    emitProductsUpdated,
    emitTeamIdUpdated,
    emitCartUpdated,
    emitWishlistUpdated,
    emitUpdateWishlist,
}