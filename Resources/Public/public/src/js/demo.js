//
// demo.js
//

const eventGrid = document.querySelector('.event__grid');
if (eventGrid) {
// two column to row transformation
  eventGrid.addEventListener('click', () => {
    eventGrid.closest('.event-teaser__title').querySelector('.event-teaser__grid').classList.remove('event-teaset--row');
    eventGrid.closest('.event-teaser__title').querySelector('.event-teaser__grid').classList.add('event-teaset--grid');
    const rowstrrucure = document.querySelector('.row--str');
    if (rowstrrucure) {
      rowstrrucure.classList.add('date--postion');
    }

    const rowGrid = document.querySelectorAll('.row-grid--2 .columnView');
    if (rowGrid.length) {
      rowGrid.forEach(($ele) => {
        $ele.classList.add('column-item-sm-6');
        $ele.classList.remove('column-item-sm-12');
        $ele.classList.remove('row-structure');
      });
    }
    // Row to two column transformation
    const columnGrid = document.querySelectorAll('.rowView .columnView');
    if (columnGrid.length) {
      columnGrid.forEach((item) => {
        item.classList.remove('column-item-sm-6');
      });
    }

    // row column to three structure
    const rowthreecolumn = document.querySelectorAll('.row-grid--3 .event-teaser');
    if (rowthreecolumn.length) {
      rowthreecolumn.forEach(($ele) => {
        $ele.classList.remove('row--view');
      });
    }

    const rowthreecolstr = document.querySelectorAll('.row-grid--3 .columnView');
    if (rowthreecolstr.length) {
      rowthreecolstr.forEach(($ele) => {
        $ele.classList.remove('column-item-md-12');
        $ele.classList.remove('twocol-to-row');
        $ele.classList.remove('row-structure');
        $ele.classList.add('column-item-md-4');
      });
    }

    // row column to four structure
    const rowfourcolumn = document.querySelectorAll('.row-grid--4 .event-teaser');
    if (rowfourcolumn.length) {
      rowfourcolumn.forEach(($ele) => {
        $ele.classList.remove('row--view');
        $ele.classList.remove('twocol-to-row');
      });
    }

    const rowfourcolstr = document.querySelectorAll('.row-grid--4 .columnView');
    if (rowfourcolstr.length) {
      rowfourcolstr.forEach(($ele) => {
        $ele.classList.remove('column-item-md-12');
        $ele.classList.remove('twocol-to-row');
        $ele.classList.remove('row-structure');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('column-item-lg-4');
        $ele.classList.add('column-item-xl-3');
        $ele.classList.add('four-column');
      });
    }

    // row to grid structure
    const rowstr = document.querySelectorAll('.class--row .column-grid');
    if (rowstr.length) {
      rowstr.forEach(($ele) => {
        $ele.classList.add('show');
      });
    }
  });
}

// row to tow column grid structure
const twocolumn = document.querySelector('.two-column');
if (twocolumn) {
  twocolumn.addEventListener('click', () => {
    const removefourcolumn = document.querySelectorAll('.column-item-md-6.column-item-lg-4.column-item-xl-3.two-colum-grid');
    if (removefourcolumn.length) {
      removefourcolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-xl-3');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.remove('twocol-to-row');
        $ele.classList.add('two-colum-grid');
      });
    }
    const removethreecolumn = document.querySelectorAll('.column-item-md-4');
    if (removethreecolumn.length) {
      removethreecolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-md-4');
        $ele.classList.add('column-item-md-6');
      });
    }
    twocolumn.classList.add('active');
    const removethreecolactive = document.querySelector('.three-column');
    removethreecolactive.classList.remove('active');
    const removefourcolactive = document.querySelector('.four-column');
    removefourcolactive.classList.remove('active');

    // classic row grid inter change
    const removegrid = document.querySelectorAll('.row--str .column-item-sm-12');
    if (removegrid.length) {
      removegrid.forEach(($ele) => {
        $ele.classList.remove('column-item-sm-12');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('two-colum-grid');
        $ele.classList.remove('twocol-to-row');
      });
    }
  });
}

const loadmorebtn = document.getElementById('loadMoreRow');
if (loadmorebtn) {
  loadmorebtn.classList.add('column-item-md-12');
  loadmorebtn.addEventListener('click', ()=> {
    const rowstr = document.querySelectorAll('.class--row .column-grid');
    if (rowstr.length) {
      rowstr.forEach(($ele) => {
        $ele.classList.remove('show');
      });
    }
    const removeactive = document.querySelectorAll('.class--row .column-grid .button');
    if (removeactive.length) {
      removeactive.forEach(($ele) => {
        $ele.classList.remove('active');
      });
    }
    const removeviewclass = document.querySelectorAll('.two-colum-grid');
    if (removeviewclass.length) {
      removeviewclass.forEach(($ele) => {
        $ele.classList.remove('column-item-md-6');
        $ele.classList.remove('column-item-md-4');
        $ele.classList.remove('column-item-xl-3');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.remove('two-colum-grid');
        $ele.classList.add('column-item-sm-12');
      });
    }
  });
}

// row to three column grid structure
const threecolumn = document.querySelector('.three-column');
if (threecolumn) {
  threecolumn.addEventListener('click', () => {
    const removefourcolumn = document.querySelectorAll('.column-item-md-6.column-item-lg-4.column-item-xl-3.two-colum-grid');
    if (removefourcolumn.length) {
      removefourcolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-xl-3');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.add('column-item-md-4');
      });
    }
    const removetwocolumn = document.querySelectorAll('.twocol-to-row');
    if (removetwocolumn.length) {
      removetwocolumn.forEach(($ele) => {
        $ele.classList.remove('twocol-to-row');
      });
    }
    const removethreecolumn = document.querySelectorAll('.column-item-md-6');
    if (removethreecolumn.length) {
      removethreecolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-md-6');
        $ele.classList.add('column-item-md-4');
      });
    }
    threecolumn.classList.add('active');
    const removetwocolactive = document.querySelector('.two-column');
    removetwocolactive.classList.remove('active');
    const removefourcolactive = document.querySelector('.four-column');
    removefourcolactive.classList.remove('active');

    const removegrid = document.querySelectorAll('.row--str .column-item-sm-12');
    if (removegrid.length) {
      removegrid.forEach(($ele) => {
        $ele.classList.remove('column-item-sm-12');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('column-item-lg-4');
        $ele.classList.add('two-colum-grid');
      });
    }
  });
}

// row to four column grid structure
const fourcolumn = document.querySelector('.four-column');
if (fourcolumn) {
  fourcolumn.addEventListener('click', () => {
    const removefourcolumn = document.querySelectorAll('.column-item-md-4');
    if (removefourcolumn.length) {
      removefourcolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-md-4');
        $ele.classList.remove('twocol-to-row');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('column-item-lg-4');
        $ele.classList.add('column-item-xl-3');
      });
    }
    const removethreecolumn = document.querySelectorAll('.column-item-md-6');
    if (removethreecolumn.length) {
      removethreecolumn.forEach(($ele) => {
        $ele.classList.remove('column-item-md-6');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('column-item-lg-4');
        $ele.classList.add('column-item-xl-3');
      });
    }
    fourcolumn.classList.add('active');
    const removetwocolactive = document.querySelector('.two-column');
    removetwocolactive.classList.remove('active');
    const removehthreecolactive = document.querySelector('.three-column');
    removehthreecolactive.classList.remove('active');

    const removegrid = document.querySelectorAll('.row--str .column-item-sm-12');
    if (removegrid.length) {
      removegrid.forEach(($ele) => {
        $ele.classList.remove('column-item-sm-12');
        $ele.classList.remove('twocol-to-row');
        $ele.classList.add('column-item-md-6');
        $ele.classList.add('column-item-lg-4');
        $ele.classList.add('column-item-xl-3');
        $ele.classList.add('two-colum-grid');
      });
    }
  });
}
const eventRow = document.querySelector('.event__row');
if (eventRow) {
  eventRow.addEventListener('click', () => {
    eventRow.closest('.event-teaser__title').querySelector('.event-teaser__grid').classList.remove('event-teaset--grid');
    eventRow.closest('.event-teaser__title').querySelector('.event-teaser__grid').classList.add('event-teaset--row');
    // remove grid class
    const rowstr = document.querySelectorAll('.class--row .column-grid');
    if (rowstr.length) {
      rowstr.forEach(($ele) => {
        $ele.classList.remove('show');
      });
    }

    // remove all class
    const removeviewclass = document.querySelectorAll('.two-colum-grid');
    if (removeviewclass.length) {
      removeviewclass.forEach(($ele) => {
        $ele.classList.remove('column-item-md-6');
        $ele.classList.remove('column-item-md-4');
        $ele.classList.remove('column-item-xl-3');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.remove('two-colum-grid');
        $ele.classList.add('column-item-sm-12');
      });
    }
    // two column to row transformation
    const addclassGrid = document.querySelectorAll('.row-grid--2 .columnView');
    if (addclassGrid.length) {
      addclassGrid.forEach((item) => {
        item.classList.add('column-item-sm-12');
        item.classList.add('row-structure');
        item.classList.remove('column-item-sm-6');
      });
    }

    // Row to two column transformation
    const rowGrid = document.querySelectorAll('.rowView .columnView');
    if (rowGrid.length) {
      rowGrid.forEach(($ele) => {
        $ele.classList.remove('column-item-md-4');
        $ele.classList.remove('column-item-md-8');
        $ele.classList.remove('column-item-sm-12');
        $ele.classList.remove('row-structure');
      });
    }

    // three column to row structure
    const threecolumn = document.querySelectorAll('.row-grid--3 .event-teaser');
    if (threecolumn.length) {
      threecolumn.forEach(($ele) => {
        $ele.classList.add('row--view');
      });
    }

    const threecolstr = document.querySelectorAll('.row-grid--3 .columnView');
    if (threecolstr.length) {
      threecolstr.forEach(($ele) => {
        $ele.classList.add('column-item-sm-12');
        $ele.classList.add('row-structure');
        $ele.classList.remove('column-item-md-4');
      });
    }

    // four column to row structure
    const fourcolumn = document.querySelectorAll('.row-grid--4 .event-teaser');
    if (fourcolumn.length) {
      fourcolumn.forEach(($ele) => {
        $ele.classList.add('row--view');
      });
    }

    const fourcolstr = document.querySelectorAll('.row-grid--4 .columnView');
    if (fourcolstr.length) {
      fourcolstr.forEach(($ele) => {
        $ele.classList.add('column-item-sm-12');
        $ele.classList.add('row-structure');
        $ele.classList.remove('column-item-md-6');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.remove('column-item-xl-3');
      });
    }

    // classic tow column grid to row
    const classicgrid = document.querySelectorAll('.row--str .rowView');
    if (classicgrid.length) {
      classicgrid.forEach(($ele) => {
        $ele.classList.remove('column-item-md-6');
        $ele.classList.remove('column-item-lg-4');
        $ele.classList.remove('column-item-xl-3');
        $ele.classList.remove('column-item-sm-12');
      });
    }

    // remove active class
    const removeactive = document.querySelector('.column-grid .active');
    if (removeactive) {
      removeactive.classList.remove('active');
    }

    const removeclassicgrid = document.querySelector('.column-grid .show');
    removeclassicgrid.classList.remove('show');
  });
}

const modal = document.querySelector('.modal');
const trigger = document.querySelector('.trigger');
const closeButton = document.querySelector('.close-button');

function toggleModal() {
  modal.classList.toggle('show-modal');
}

function windowOnClick(event) {
  if (event.target === modal) {
    toggleModal();
  }
}

if (trigger) {
  trigger.addEventListener('click', toggleModal);
}

if (closeButton) {
  closeButton.addEventListener('click', toggleModal);
}
window.addEventListener('click', windowOnClick);
