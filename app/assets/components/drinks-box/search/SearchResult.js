import React from 'react';

const SearchResult = ({name, index, addProduct}) => {
   return (
      <div
         className="search__result"
         style={{
            background: index % 2 ? 'var(--white)' : 'var(--yellow)',
         }}
         onClick={() => addProduct(name)}
      >
         {name}
      </div>
   );
};

export default SearchResult;
