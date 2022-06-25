import React from 'react';

const SortBox = ({ sortOptions, setSortOptions }) => {
   const selectOption = (e) => {
      setSortOptions(
         sortOptions.map((option) =>
            option.name == e.target.textContent
               ? { ...option, active: true }
               : { ...option, active: false }
         )
      );
   };

   return (
      <div className="drinks__sort-options-box">
         {sortOptions.map((option) => (
            <span
               key={option.id}
               className={
                  option.active
                     ? 'drinks__sort-option drinks__sort-active'
                     : 'drinks__sort-option'
               }
               onClick={selectOption}
            >
               {option.name}
            </span>
         ))}
      </div>
   );
};

export default SortBox;
