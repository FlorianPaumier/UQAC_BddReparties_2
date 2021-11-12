import React
    from "react";

const Pagination = ({
                        currentPage,
                        count,
                        total,
                        nbParPage,
                        changePage
                    }) => {

    const defaultPageQuantityAround = 3
    const minimumPageQuantityAround = Math.min(currentPage - 1, total - currentPage)
    const conditionBefore = currentPage !== 1 && minimumPageQuantityAround <= currentPage - 1
    const conditionAfter = currentPage !== total && minimumPageQuantityAround <= total - currentPage
    const PageQuantityAroundBefore = conditionBefore ? defaultPageQuantityAround : minimumPageQuantityAround
    const PageQuantityAroundAfter = conditionAfter ? defaultPageQuantityAround : minimumPageQuantityAround
    const querySeparator = "?"
    let hideBefore = false
    let hideAfter = false

    const generatePage = () => {
        const pages = [];
        for (let i = 1; i < total; i++) {
            if (0 < (currentPage - PageQuantityAroundBefore) - i && !hideBefore) {
                pages.push(
                    <button
                        key={i}
                        className="flex items-center px-4 py-2  text-gray-700 transition-colors border-t border-b
						 duration-200 transform bg-white  dark:bg-gray-800 dark:text-gray-200
						  hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200">...</button>)
                hideBefore = true;
            } else if (0 > (currentPage - PageQuantityAroundBefore) - i && !hideAfter) {
                pages.push(
                    <button
                        key={i}
                        className="flex items-center px-4 py-2  text-gray-700 transition-colors border-t border-b
						 duration-200 transform bg-white  dark:bg-gray-800 dark:text-gray-200
						  hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200">...</button>)
                hideAfter = true;
            } else if (0 > (currentPage + PageQuantityAroundAfter) - i || 0 < (currentPage - PageQuantityAroundBefore) - i) {
            } else
                pages.push(
                    <button
                        onClick={() => changePage(i)}
                        key={i}
                        href="{{ querySeparator }}&page={{ i }}"
                        className={`${currentPage == i ? 'dark:bg-gray-800 bg-blue-600 text-white' : 'dark:text-gray-200 text-gray-700'}
						    ${i % 2 ? 'border-l border-r' : ''}
							flex items-center px-4 py-2  border-t border-b
							transition-colors duration-200 transform bg-white  dark:bg-gray-800
							 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200`}>
                        {i}
                    </button>)
        }
        return pages;
    }

    return <section
        className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div>
            <p className="text-sm text-gray-700">
                de
                <span
                    className="font-medium"> {currentPage * nbParPage - (nbParPage - 1)} </span>
                à
                {currentPage == total && (
                    <span
                        className="font-medium"> {total} </span>)}
                {currentPage !== total && (
                    <span
                        className="font-medium"> {
                        currentPage * nbParPage} </span>)}
                sur
                <span
                    className="font-medium"> {count} </span>
                résultats
            </p>
        </div>
        <div>
            <nav
                className="pagination flex"
                aria-label="Pagination">
                <button
                    onClick={() => changePage(1)}
                    className="{% if currentPage == 1 or currentPage  - 1 == 1pointer-events-none{% endif
				   flex items-center px-4 py-2  text-gray-700 transition-colors duration-200
				   transform bg-white rounded-l-md dark:bg-gray-800 dark:text-gray-200 hover:bg-indigo-600
				   dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200 border border-r-0">
                    <span
                        className="sr-only">Précédant</span>
                    <i className="fas fa-angle-double-left"></i>
                </button>
                <button
                    onClick={() => changePage(currentPage - 1)}
                    className="{% if currentPage == 1pointer-events-none{% endif
				   flex items-center px-4 py-2 text-gray-700 transition-colors duration-200 transform
				   bg-white  dark:bg-gray-800 dark:text-gray-200 hover:bg-indigo-600
				   dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200 border">
                    <span
                        className="sr-only">Précédant</span>
                    <svg
                        className="h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true">
                        <path
                            fillRule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clipRule="evenodd"/>
                    </svg>
                </button>
                {generatePage().map(page => page)}
                <button
                    onClick={() => changePage(currentPage + 1)}
                    className="{% if currentPage == total or pagination.totalCount == 0pointer-events-none {% endif
                    flex items-center px-4 py-2  text-gray-700 transition-colors border-t border-b
                    duration-200 transform bg-white border dark:bg-gray-800 dark:text-gray-200
                    hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200">
                    <span
                        className="sr-only">Suivant</span>
                    <svg
                        className="h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true">
                        <path
                            fillRule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clipRule="evenodd"/>
                    </svg>
                </button>
                <button
                    onClick={() => changePage(total)}
                    className="{% if
					   currentPage == total
					   or currentPage + 1 == total
					   or pagination.totalCount == 0pointer-events-none {% endif
					   flex items-center px-4 py-2  text-gray-700 transition-colors duration-200
					   transform bg-white rounded-r-md dark:bg-gray-800 dark:text-gray-200  border border-l-0
					    hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white dark:hover:text-gray-200">
                    <span
                        className="sr-only">Suivant</span>
                    <i className="fas fa-angle-double-right"></i>
                </button>
            </nav>
        </div>
    </section>
}

export default Pagination
