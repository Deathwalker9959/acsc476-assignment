import dashboard from "../components/dashboard.js";
import cardDeck from "../components/card-deck.js";
import teamsDropdown from "../components/teams-dropdown.js";

const handleResize = () => {
    if ($(window).width() >= 768) {  // Bootstrap's "md" breakpoint
        $('#navbarNav').addClass('show')
    } else {
        $('#navbarNav').removeClass('show')
    }
}

const loadData = async () => {
    try {
        const response = await axios.get('/partner/api/shops');
        const shops = response.data;
        cardDeck.data.shops = shops;
        teamsDropdown.data.teams = shops;
    } catch (error) {
        console.error(error);
    }
}

const main = () => {
    component("#spa-container", dashboard.template);
    // component("#spa-container", cardDeck.template);
    component("#teams-dropdown", teamsDropdown.template);

    document.querySelector("#teams-dropdown").addEventListener('reef:render', teamsDropdown.addEvents);
    document.querySelector("#spa-container").addEventListener('reef:render', dashboard.addEvents);
}

$(window).on('load', main);
$(window).on('load', handleResize);
$(window).on('load', loadData);
$(window).on('resize', handleResize);
