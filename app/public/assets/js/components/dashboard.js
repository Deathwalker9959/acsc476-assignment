import cardDeck from "../components/card-deck.js";
import teamProducts from "../components/team-product.js";
import wishlist from "../components/wishlist.js";
import { emitTeamIdUpdated } from "../mixins/events.js";

/*
* Set product display to editor mode
*/

let data = store({
    selectedTeam: null,
    isEditor: false,
});

let template = () => {
    let { selectedTeam } = data;

    if (!selectedTeam) {
        return cardDeck.template();
    }


    return /*html*/`
    <div class="card-deck">
        ${teamProducts.template()}
    </div>
    `;
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;

    if (data.selectedTeam) {
        $('div.card.shop[aria-id]').off('click');

        if (!data.isEditor) {
            wishlist.data.selectedTeam = e.detail;
            wishlist.loadData();
        }
    }
}

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);
    teamProducts.addEvents();
    teamProducts.data.isEditor = data.isEditor;

    $("div.card.shop > a.stretched-link").off('click');
    $('div.card.shop[aria-id]').off('click');
    $('div.card.shop[aria-id]').on('click', function (e) {
        const teamId = $(this).attr('aria-id');
        emitTeamIdUpdated(teamId);
        e.stopImmediatePropagation();
    });
}

export default {
    data,
    template,
    addEvents,
};
