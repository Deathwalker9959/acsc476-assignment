let shopCardTemplate = (shop) => {
    return /*html*/ `
    <div class="card shop" aria-id="${shop.id}">
        <a href="#" class="stretched-link"></a>
        <div class="row h-100">
            <div class="col-md-12 h-100">
            ${shop?.photo_url ? /*html*/`
                <img
                    class="w-100 overflow-clip card-img"
                    height="200"
                    ${shop?.photo_url ? "src=" + shop?.photo_url : ""}
                />
                `
            : /*html*/`
                <svg
                    class="w-100 overflow-clip card-img"
                    height="200"
                >
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
                `
        }
            </div>
        </div>
        <div class="row shop-details">
            <div class="col-md-8 my-auto">
                <strong>${shop?.name ?? ""}</strong>
                <br />
                <small class="text-secondary shop-details-secondary">${shop?.category ?? ""}</small>
            </div>
            <div class="delivery-holder text-center">
                <i class="fa fa-motorcycle">${shop?.delivery_price ? `<span class="mx-1">&euro;</span>${shop.delivery_price}` : " free"}</i>
            </div>
        </div>
    </div>
    `;
};

let shopCardSpinnerTemplate = () => {
    return /*html*/ `
    <div class="card shop">
        <div class="row h-100 shop-image loading">
            <div class="d-flex col-md-12 ">
                <div class="d-flex my-auto mx-auto align-middle blur">
                    <div class="spinner-border text-center" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row shop-details placeholder-glow loading">
            <div class="col-md-8">
                <span class="placeholder col-10 rounded-pill"></span>
                <span class="placeholder col-6 rounded-pill"></span>
            </div>
            <div class="col-md-4">
                <span class="placeholder col-12 rounded-pill"></span>
            </div>
        </div>
    </div>
`;
};

let cardDeckTemplate = (firstCard, secondCard) => {
    return /*html*/`
    <div class="row row-cols-md-2 row-cols-lg-3">
        <div class="col mx-0 my-4">
            ${firstCard}
        </div>
        <div class="d-md-none"></div>
        <div class="col mx-0 my-4">
            ${secondCard}
        </div>
    </div>
    `;
};

let data = store({
    shops: [
    ],
});

let template = () => {
    let { shops } = data;

    let cards = shops.reduce((acc, shop, index) => {
        let nextShop = shops[index + 1];
        if (index % 2 == 0) {
            acc.push(cardDeckTemplate(shopCardTemplate(shop), nextShop ? shopCardTemplate(nextShop) : ""));
        }
        return acc;
    }, []);

    return /*html*/`
        <div class="card-deck">
            ${cards.length > 0 ? cards.join('') : cardDeckTemplate(shopCardSpinnerTemplate(), shopCardSpinnerTemplate())}
        </div>
`;
};

export default {
    data,
    template,
};
