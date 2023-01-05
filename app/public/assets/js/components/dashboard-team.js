import cardDeck from "../components/card-deck.js";

let data = store({
    selectedTeam: null,
});

let template = () => {
    let { selectedTeam, teams } = data;

    if (!selectedTeam) {
        return cardDeck.template();
    }

    return /*html*/`
        <div class="card-deck">
            ${cards.join('')}
        </div>
    `;
};

const addEvents = () => {
    $('div.card.shop').on('click', function () {
        data.selectedTeam = $(this).attr('aria-id');
    });
}


export default {
    data,
    template,
    addEvents,
};
