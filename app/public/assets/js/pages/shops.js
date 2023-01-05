import cardDeck from "../components/card-deck.js";

const handleResize = () => {
  if ($(window).width() < 768) {  // Bootstrap's "md" breakpoint
    $('#collapseSort, #collapseFilters').removeClass('show')
  } else {
    $('#collapseSort, #collapseFilters').addClass('show')
  }
}

const loadData = async () => {
  try {
    const response = await axios.get('/api/shops');
    const shops = response.data;
    cardDeck.data.shops = shops;
  } catch (error) {
    console.error(error);
  }
}


const main = () => {
  component("#shop-cards", cardDeck.template);
}

$(window).on('load', main);
$(window).on('load', handleResize);
$(window).on('load', loadData);
$(window).on('resize', handleResize);
