import React from 'react';
import SortBox from './SortBox';

const DrinksBox = () => {
  return (
    <div className="drinks">
      <div className="drinks__top">
        <span className="drinks__counter">Znaleziono 15 drinków</span>
        <SortBox />
      </div>
    </div>
  );
};

export default DrinksBox;
