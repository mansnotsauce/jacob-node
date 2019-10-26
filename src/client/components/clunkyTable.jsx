import Link from './link'

export default function ClunkyTable({
    title,
    searchString,
    onSearchStringChange,
    onDelete,
    tableHead,
    tableBody,
}) {
    
    // The grid layout that was used before requires the head and body to be define twice.
    // I think it's necessary for spacing purposes?
    // Don't ask me why but it works and looks alright, so leaving it for now.

    return (
        <div>
            <div className="section-table-name">{title}</div>
            <div className="row">
                <div className="col-xs-6 bulk-buttons">                      
                    <input
                        type="text"
                        name="pwr_search"
                        placeholder="Search..."
                        onChange={e => onSearchStringChange(e.target.value)}
                        value={searchString}
                    />
                    <button
                        className="deleteSelectedBtn"
                        onClick={() => onDelete()}
                    >Delete Selected</button>
                </div>
                <div className="col-xs-6 alignRight">
                    <Link to="/createUser/">
                        <button className="addUserBtn">
                            Create New User
                        </button>
                    </Link>
                </div>
            </div>
            <div className="clear30" />
            <div className="table-container admin-dashboard">
                <div className="table-header">
                    <table className="table" param="pwrstation">
                        {tableHead}
                        {tableBody}
                    </table>
                </div>
                <div className="table-content">
                    <table className="table">
                        {tableHead}
                        {tableBody}
                    </table>
                </div>
            </div>
        </div>
    )
}
