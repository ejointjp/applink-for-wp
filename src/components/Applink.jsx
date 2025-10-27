import { HiPlay } from 'react-icons/hi';
import { StoreIcon } from './StoreIcon';

const Applink = ({ app }) => {
	return (
		<div className={`alfwp alfwp-${app.type}`}>
			<a
				className='alfwp-figure'
				href={app.url}
				target='_blank'
				rel='noopener nofollow noreferrer'
			>
				<img className='alfwp-img' src={app.iconUrl} alt={app.title} />
			</a>
			<div className='alfwp-content'>
				<a
					className='alfwp-title'
					href={app.url}
					target='_blank'
					rel='noopener nofollow noreferrer'
				>
					{app.title}
				</a>
				<div className='alfwp-artist'>{app.artist}</div>

				<div className='alfwp-btns'>
					{app.previewUrl && (
						<a
							className='alfwp-audition alfwp-btn'
							href={app.previewUrl}
							target='_blank'
							rel='noopener nofollow noreferrer'
						>
							<HiPlay />
							<span className='alfwp-btn-label'>試聴</span>
						</a>
					)}
					<a
						className='alfwp-store alfwp-btn'
						href={app.url}
						target='_blank'
						rel='noopener nofollow noreferrer'
					>
						<StoreIcon type={app.type} />
					</a>
				</div>
			</div>
		</div>
	);
};

export default Applink;
