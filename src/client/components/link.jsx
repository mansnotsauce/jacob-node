import { Link as ReactRouterDomLink } from 'react-router-dom'

export default function Link({ to, ...props }) {
    return <ReactRouterDomLink to={to} {...props} />
}
